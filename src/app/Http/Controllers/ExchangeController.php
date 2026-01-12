<?php

namespace App\Http\Controllers;

use App\Models\Exchange;
use App\Models\Room;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

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
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:500'],
            'offered_crop_name' => ['required', 'string', 'max:50'],
            'desired_crop_name' => ['required', 'string', 'max:50'],
            'area' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('exchanges', 'public');
        }

        $exchange = Exchange::create([
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
            ->route('exchanges.show', $exchange)
            ->with('status', '物々交換の投稿が完了しました！');
    }

    /**
     * 詳細（チャット表示）
     */
    public function show(Exchange $exchange): View
    {
        // 出品者 or 承諾者のみ閲覧可
        if (
            Auth::id() !== $exchange->proposer_user_id &&
            Auth::id() !== $exchange->receiver_user_id
        ) {
            abort(403);
        }

        $room = $exchange->room;

        $messages = collect();
        if ($room) {
            $messages = $room->messages()
                ->with('user')
                ->orderBy('created_at', 'asc')
                ->get();
        }

        return view('exchanges.show', [
            'exchange' => $exchange,
            'room'     => $room,
            'messages' => $messages,
        ]);
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

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:100'],
            'description' => ['required', 'string', 'max:500'],
            'offered_crop_name' => ['required', 'string', 'max:50'],
            'desired_crop_name' => ['required', 'string', 'max:50'],
            'area' => ['nullable', 'string', 'max:50'],
            'image' => ['nullable', 'image', 'max:2048'],
        ]);

        if ($request->hasFile('image')) {
            if ($exchange->image_path) {
                Storage::disk('public')->delete($exchange->image_path);
            }

            $validated['image_path'] =
                $request->file('image')->store('exchanges', 'public');
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
     * 承諾・拒否（★最重要）
     */
    public function updateStatus(
        Exchange $exchange,
        string $status
    ): RedirectResponse {

        // 投稿者は操作不可
        if ($exchange->proposer_user_id === Auth::id()) {
            abort(403);
        }

        if (!in_array($status, ['accepted', 'rejected'], true)) {
            abort(400);
        }

        DB::transaction(function () use ($exchange, $status) {

            if ($status === 'accepted') {

                // ① exchange 更新
                $exchange->update([
                    'receiver_user_id' => Auth::id(),
                    'status' => 'accepted',
                ]);

                // ★ 超重要：最新状態を取得
                $exchange->refresh();

                // ② room 作成 or 取得
                $room = Room::firstOrCreate([
                    'exchange_id' => $exchange->id,
                ]);

                // ③ proposer + receiver を確実に登録
                $userIds = array_filter([
                    $exchange->proposer_user_id,
                    $exchange->receiver_user_id,
                ]);

                $room->users()->syncWithoutDetaching($userIds);
            }

            if ($status === 'rejected') {
                $exchange->update(['status' => 'rejected']);
            }
        });

        return redirect()
            ->back()
            ->with('status', "交換を「{$status}」しました");
    }
}
