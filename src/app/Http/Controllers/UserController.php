<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Exchange;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * ユーザーマイページ
     */
    public function show(User $user): View
    {
        // ================= 通常の投稿一覧（タグ付き） =================
        $posts = $user->posts()
            ->with('tags')
            ->latest()
            ->paginate(10);

        // ================= 物々交換投稿一覧（提案者としての投稿） =================
        $exchanges = Exchange::where('proposer_user_id', $user->id)
            ->latest()
            ->paginate(5, ['*'], 'exchanges_page');

        return view('users.show', compact('user', 'posts', 'exchanges'));
    }
}
