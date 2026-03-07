<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ExchangeController extends Controller
{
    /**
     * 物々交換一覧
     */
    public function index(): View
    {
        $exchanges = Exchange::with('proposer')
            ->latest()
            ->get();

        return view('exchanges.index', compact('exchanges'));
    }

    /**
     * 投稿フォーム
     */
    public function create(): View
    {
        return view('exchanges.create');
    }

    /**
     * 投稿保存
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required','string','max:100'],
            'description' => ['required','string','max:500'],
            'offered_crop_name' => ['required','string','max:50'],
            'desired_crop_name' => ['required','string','max:50'],
            'area' => ['nullable','string','max:50'],
            'image' => ['nullable','image','max:2048'],
        ]);

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('exchanges','public');
        }

        Exchange::create([
            'proposer_user_id' => Auth::id(),
            'receiver_user_id' => null,
            'post_id' => null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'offered_crop_name' => $validated['offered_crop_name'],
            'desired_crop_name' => $validated['desired_crop_name'],
            'area' => $validated['area'] ?? Auth::user()->area,
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('exchanges.index')
            ->with('status','物々交換の投稿が完了しました！');
    }

    /**
     * 詳細
     */
    public function show(Exchange $exchange): View
    {
        return view('exchanges.show', compact('exchange'));
    }

    /**
     * 編集
     */
    public function edit(Exchange $exchange): View
    {
        if ($exchange->proposer_user_id !== Auth::id()) {
            abort(403);
        }

        return view('exchanges.edit', compact('exchange'));
    }

    /**
     * 更新
     */
    public function update(Request $request, Exchange $exchange): RedirectResponse
    {
        if ($exchange->proposer_user_id !== Auth::id()) {
            abort(403);
        }

        if ($exchange->status === 'completed') {
            return back()->with('error','完了済みの取引は編集できません');
        }

        $validated = $request->validate([
            'title' => ['required','string','max:100'],
            'description' => ['required','string','max:500'],
            'offered_crop_name' => ['required','string','max:50'],
            'desired_crop_name' => ['required','string','max:50'],
            'area' => ['nullable','string','max:50'],
            'image' => ['nullable','image','max:2048'],
        ]);

        if ($request->hasFile('image')) {

            if ($exchange->image_path) {
                Storage::disk('public')->delete($exchange->image_path);
            }

            $validated['image_path'] =
                $request->file('image')->store('exchanges','public');
        }

        $exchange->update($validated);

        return redirect()
            ->route('exchanges.show',$exchange)
            ->with('status','投稿を更新しました');
    }

    /**
     * 削除
     */
    public function destroy(Exchange $exchange): RedirectResponse
    {
        if ($exchange->proposer_user_id !== Auth::id()) {
            abort(403);
        }

        if ($exchange->image_path) {
            Storage::disk('public')->delete($exchange->image_path);
        }

        $exchange->delete();

        return redirect()
            ->route('exchanges.index')
            ->with('success','投稿を削除しました');
    }

    /**
     * ★ 取引完了
     * 出品者のみ押せる
     */
    public function complete(Exchange $exchange): RedirectResponse
    {
        // 出品者のみ
        if (Auth::id() !== $exchange->proposer_user_id) {
            abort(403);
        }

        // すでに完了している場合
        if ($exchange->status === 'completed') {
            return back();
        }

        $exchange->update([
            'status' => 'completed',
            'completed_at' => now(),
        ]);

        return back()->with('status','取引を完了しました');
    }
}