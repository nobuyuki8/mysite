<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    /**
     * コメント保存処理
     */
    public function store(Request $request, Post $post)
    {
        // バリデーション
        $request->validate([
            'comment' => 'required|string|max:500',
        ]);

        // コメント作成
        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $post->id,
            'content' => $request->comment,  // ⭐重要：content に comment の値を入れる
        ]);

        return redirect()
            ->route('posts.show', $post->id)
            ->with('success', 'コメントを追加しました');
    }

    /**
     * コメント削除処理
     */
    public function destroy(Comment $comment)
    {
        // 自分のコメントのみ削除可
        if ($comment->user_id !== Auth::id()) {
            abort(403, '権限がありません');
        }

        $comment->delete();

        return back()->with('success', 'コメントを削除しました');
    }
}
