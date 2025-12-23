<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    /**
     * 投稿一覧（検索対応）
     */
    public function index(Request $request)
    {
        $query = Post::with(['user', 'tags', 'likers']);

        // キーワード検索
        if ($request->filled('keyword')) {
            $query->where('content', 'like', "%{$request->keyword}%");
        }

        // 23区の定義
        $tokyo23 = [
            '千代田区','中央区','港区','新宿区','文京区','台東区','墨田区','江東区',
            '品川区','目黒区','大田区','世田谷区','渋谷区','中野区','杉並区','豊島区',
            '北区','荒川区','板橋区','練馬区','足立区','葛飾区','江戸川区'
        ];

        // エリア検索
        if ($request->filled('area')) {
            $area = $request->area;

            if ($area === 'tokyo23') {
                $query->whereHas('user', fn($q) => $q->whereIn('area', $tokyo23));
            } elseif ($area === 'outside23') {
                $query->whereHas('user', fn($q) => $q->whereNotIn('area', $tokyo23));
            } else {
                $query->whereHas('user', fn($q) => $q->where('area', $area));
            }
        }

        // タグ検索
        if ($request->filled('tag')) {
            $query->whereHas('tags', fn($q) => $q->where('name', 'like', "%{$request->tag}%"));
        }

        // 最新順
        $posts = $query->orderBy('created_at','desc')
            ->paginate(10)->withQueryString();

        return view('posts.index', compact('posts'));
    }

    /**
     * 投稿詳細（コメント表示）
     */
    public function show(Post $post)
    {
        // コメントも取得
        $post->load(['user', 'tags', 'comments.user']);

        return view('posts.show', compact('post'));
    }

    /**
     * 新規投稿画面
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * 投稿保存（タグ反映 ＋ IMEゴミ文字対策）
     */
    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();

        // 画像アップロード
        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('posts', 'public')
            : null;

        // 投稿作成
        $post = Post::create([
            'user_id' => Auth::id(),
            'content' => $validated['content'],
            'image'   => $imagePath,
        ]);

        /**
         * -------- タグ処理 --------
         */
        if (!empty($validated['tags'])) {

            $rawTags = preg_split('/[\p{Z}\s、,#]+/u', $validated['tags'], -1, PREG_SPLIT_NO_EMPTY);
            $tagIds = [];

            foreach ($rawTags as $tagName) {

                $tagName = trim($tagName);

                if (mb_strlen($tagName) <= 1) continue;
                if (!preg_match('/[\p{L}\p{N}]/u', $tagName)) continue;

                $tag = Tag::firstOrCreate(['name' => $tagName]);
                $tagIds[] = $tag->id;
            }

            if (!empty($tagIds)) {
                $post->tags()->sync($tagIds);
            }
        }

        return redirect()->route('posts.index')->with('success', '投稿しました！');
    }

    /**
     * いいね
     */
    public function like(Post $post)
    {
        $post->likers()->syncWithoutDetaching([Auth::id()]);

        return response()->json([
            'status'=>'success',
            'liked'=>true,
            'count'=>$post->likers()->count(),
        ]);
    }

    /**
     * いいね解除
     */
    public function unlike(Post $post)
    {
        $post->likers()->detach(Auth::id());

        return response()->json([
            'status'=>'success',
            'liked'=>false,
            'count'=>$post->likers()->count(),
        ]);
    }

    /**
     * 投稿削除
     */
    public function destroy(Post $post)
    {
        if ($post->user_id !== Auth::id()) {
            abort(403, '権限なし');
        }

        if ($post->image) {
            $imagePath = 'posts/' . basename($post->image);
            if (Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
        }

        $post->tags()->detach();
        $post->likers()->detach();
        $post->delete();

        return redirect()->route('posts.index')->with('success', '投稿を削除しました');
    }
}

