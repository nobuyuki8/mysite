<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Message;
use App\Models\Exchange;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    /**
     * チャットルーム表示
     */
    public function show(Exchange $exchange)
    {
        // ★ 必須：投稿者・相手ユーザーを確実に読み込む
        $exchange->loadMissing(['proposer', 'receiver']);

        // 権限チェック（出品者 or 受信者のみ）
        if (
            Auth::id() !== $exchange->proposer_user_id &&
            Auth::id() !== $exchange->receiver_user_id
        ) {
            abort(403, 'このチャットにアクセスする権限がありません。');
        }

        // ルーム取得 or 作成
        $room = Room::firstOrCreate([
            'exchange_id' => $exchange->id,
        ]);

        // メッセージ一覧（ユーザー情報込み）
        $messages = Message::with('user')
            ->where('room_id', $room->id)
            ->orderBy('created_at', 'asc')
            ->get();

        // ★ 相手ユーザーを確定（必ずどちらかになる）
        if (Auth::id() === $exchange->proposer_user_id) {
            $partner = $exchange->receiver;
        } else {
            $partner = $exchange->proposer;
        }

        return view('rooms.show', [
            'room'     => $room,
            'messages' => $messages,
            'exchange' => $exchange,
            'partner'  => $partner,
        ]);
    }

    /**
     * メッセージ送信
     */
    public function send(Request $request, Room $room)
    {
        $request->validate([
            'message' => 'required|string|max:2000',
        ]);

        // 交換情報取得（安全のため eager load）
        $exchange = $room->exchange()->with(['proposer', 'receiver'])->first();

        // 権限チェック
        if (
            Auth::id() !== $exchange->proposer_user_id &&
            Auth::id() !== $exchange->receiver_user_id
        ) {
            abort(403, 'このチャットに投稿する権限がありません。');
        }

        // メッセージ保存
        Message::create([
            'room_id'        => $room->id,
            'sender_user_id' => Auth::id(),
            'user_id'        => Auth::id(), // user リレーション用
            'message'        => $request->message,
        ]);

        return back();
    }
}
