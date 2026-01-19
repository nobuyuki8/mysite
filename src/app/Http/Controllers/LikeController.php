<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    /**
     * コンストラクタ
     * いいねはログイン必須
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * いいね ON / OFF を切り替える（トグル）
     *
     * POST /posts/{post}/like
     */
    public function toggle(Post $post): JsonResponse
    {
        $user = Auth::user();

        // 念のため（通常ここには来ない）
        if (!$user) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Unauthenticated',
            ], 401);
        }

        // すでにいいねしているか確認
        $alreadyLiked = $post->likers()
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyLiked) {
            // いいね解除
            $post->likers()->detach($user->id);
        } else {
            // いいね追加（重複防止）
            $post->likers()->syncWithoutDetaching([$user->id]);
        }

        // 最新のいいね数
        $count = $post->likers()->count();

        return response()->json([
            'status' => 'success',
            'liked'  => !$alreadyLiked,
            'count'  => $count,
        ]);
    }
}
