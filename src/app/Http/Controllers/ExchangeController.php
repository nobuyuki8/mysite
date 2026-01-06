<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Room; // 修正済み：message_rooms ではなく rooms
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ExchangeController extends Controller
{
    /**
     * 物々交換投稿の一覧
     */
    public function index(): View
    {
        $exchanges = Exchange::with('proposer')->latest()->get();
        return view('exchanges.index', compact('exchanges'));
    }

    /**
     * 物々交換投稿フォーム
     */
    public function create(): View
    {
        return view('exchanges.create');
    }

    /**
     * 保存
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:500'],
            'offered_crop_name' => ['required', 'string', 'max:50'],
            'desired_crop_name' => ['required', 'string', 'max:50'],
            'area' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:2048'], // 画像追加
        ]);

        // 画像があれば保存
        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('exchanges', 'public');
        }

        $exchange = Exchange::create([
            'proposer_user_id' => $request->user()->id,
            'receiver_user_id' => null,
            'post_id' => null,
            'title' => $validated['title'],
            'description' => $validated['description'],
            'offered_crop_name' => $validated['offered_crop_name'],
            'desired_crop_name' => $validated['desired_crop_name'],
            'area' => $validated['area'] ?? $request->user()->area,
            'image_path' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('exchanges.show', $exchange)
            ->with('status', '物々交換の投稿が完了しました！');
    }

    /**
     * 物々交換詳細（チャット表示付き）
     */
    public function show(Exchange $exchange): View
    {
        if (
            $exchange->proposer_user_id !== Auth::id() &&
            $exchange->receiver_user_id !== Auth::id()
        ) {
            abort(403);
        }

        // rooms テーブルを参照
        $room = Room::firstOrCreate([
            'exchange_id' => $exchange->id
        ]);

        $messages = Message::with('user')
            ->where('room_id', $room->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('exchanges.show', compact('exchange', 'room', 'messages'));
    }

    /**
     * 編集フォーム
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

        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string|max:500',
            'offered_crop_name' => 'required|string|max:50',
            'desired_crop_name' => 'required|string|max:50',
            'area' => 'nullable|string|max:50',
            'image' => ['nullable', 'image', 'max:2048'], // 画像更新対応
        ]);

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('exchanges', 'public');
            $validated['image_path'] = $imagePath;
        }

        $exchange->update($validated);

        return redirect()
            ->route('exchanges.show', $exchange)
            ->with('status', '投稿を更新しました');
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
            ->with('success', '投稿を削除しました');
    }

    /**
     * 承諾・拒否
     */
    public function updateStatus(Exchange $exchange, string $status): RedirectResponse
    {
        if ($exchange->receiver_user_id !== Auth::id()) {
            abort(403);
        }

        if (!in_array($status, ['accepted', 'rejected'])) {
            abort(400);
        }

        $exchange->update(['status' => $status]);

        if ($status === 'accepted') {
            $room = Room::firstOrCreate([
                'exchange_id' => $exchange->id
            ]);

            $room->users()->syncWithoutDetaching([
                $exchange->proposer_user_id,
                $exchange->receiver_user_id
            ]);
        }

        return redirect()
            ->back()
            ->with('status', "交換を「{$status}」しました");
    }
}