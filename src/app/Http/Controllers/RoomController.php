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
        // 関連ロード
        $exchange->loadMissing(['proposer', 'receiver', 'room.users']);

        /**
         * ① ルーム取得 or 作成
         *    承諾前でも作る（ただし user は安全に）
         */
        $room = Room::firstOrCreate([
            'exchange_id' => $exchange->id,
        ]);

        /**
         * ② ★null を除外して参加ユーザーを登録
         *    出品者・承諾者以外の閲覧ユーザーはここで追加しなくてもOK
         */
        $userIds = array_filter([
            $exchange->proposer_user_id,
            $exchange->receiver_user_id,
        ]);
        $room->users()->syncWithoutDetaching($userIds);

        /**
         * ③ 権限チェックは削除（承諾前でも誰でも閲覧可能）
         */

        /**
         * ④ メッセージ取得
         */
        $messages = $room->messages()
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        /**
         * ⑤ partner 計算（ログインユーザー以外）
         */
        $partner = $room->users
            ->where('id', '!=', Auth::id())
            ->first();

        return view('rooms.show', compact(
            'room',
            'messages',
            'exchange',
            'partner'
        ));
    }

    /**
     * メッセージ送信
     */
    public function send(Request $request, Room $room)
    {
        $request->validate([
            'message' => ['required', 'string', 'max:2000'],
        ]);

        /**
         * ★投稿権限も削除（誰でも送信可能）
         * もし後で承諾者のみ制限に戻す場合はここを調整
         */

        /**
         * メッセージ保存
         */
        Message::create([
            'room_id'        => $room->id,
            'user_id'        => Auth::id(),
            'sender_user_id' => Auth::id(),
            'message'        => $request->message,
        ]);

        return back();
    }
}
