<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * メッセージ送信
     */
    public function store(Request $request, $roomId)
    {
        // バリデーション
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        // メッセージ保存
        Message::create([
            'room_id'        => $roomId,
            'sender_user_id' => Auth::id(),
            'message'        => $request->message,
        ]);

        return redirect()->back()->with('success', 'メッセージを送信しました。');
    }
}
