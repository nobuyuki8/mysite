<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class LikeController extends Controller
{
    /**
     * いいねを追加する (POST /posts/{post}/like)
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function store(Request $request, Post $post): JsonResponse
    {
        $user = $request->user();

        // すでにいいねしていなければ追加
        if (!$post->likers()->where('user_id', $user->id)->exists()) {
            $post->likers()->attach($user->id);
        }

        // いいね数を再取得
        $likeCount = $post->likers()->count();

        // JSONで返す
        return response()->json([
            'status' => 'success',
            'liked' => true,
            'count' => $likeCount,
        ]);
    }

    /**
     * いいねを削除（解除）する (DELETE /posts/{post}/unlike)
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Post $post
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function destroy(Request $request, Post $post): JsonResponse
    {
        $user = $request->user();

        // いいねしていれば解除
        if ($post->likers()->where('user_id', $user->id)->exists()) {
            $post->likers()->detach($user->id);
        }

        // いいね数を再取得
        $likeCount = $post->likers()->count();

        // JSONで返す
        return response()->json([
            'status' => 'success',
            'liked' => false,
            'count' => $likeCount,
        ]);
    }
}
