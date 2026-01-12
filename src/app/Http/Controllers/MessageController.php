<?php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class MessageController extends Controller
{
    /**
     * メッセージ削除
     *
     * @param  \App\Models\Message  $message
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Message $message): RedirectResponse
    {
        // 自分のメッセージ以外は削除不可
        if ($message->user_id !== Auth::id()) {
            abort(403, 'このメッセージを削除する権限がありません。');
        }

        // メッセージ削除
        $message->delete();

        return back()->with('success', 'メッセージを削除しました。');
    }
}
