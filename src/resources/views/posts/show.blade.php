<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            投稿詳細
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto py-6">

        {{-- 成功メッセージ --}}
        @if (session('success'))
            <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        {{-- 投稿本体 --}}
        <div class="bg-white shadow p-6 rounded-lg mb-6">

            {{-- 投稿者 --}}
            <div class="text-gray-700 mb-3">
                <strong>{{ $post->user->name }}</strong>
                <span class="text-sm text-gray-500">（{{ $post->created_at->format('Y-m-d H:i') }}）</span>
            </div>

            {{-- 投稿内容 --}}
            <p class="mb-4 whitespace-pre-wrap">{{ $post->content }}</p>

            {{-- 画像 --}}
            @if ($post->image)
                <img src="{{ asset('storage/'.$post->image) }}" class="rounded mb-4 max-h-80 object-cover">
            @endif

            {{-- タグ --}}
            <div class="mb-4">
                @foreach ($post->tags as $tag)
                    <a href="/posts?tag={{ $tag->name }}" class="bg-blue-100 text-blue-700 px-2 py-1 rounded text-sm mr-2">
                        #{{ $tag->name }}
             </a>
                @endforeach
            </div>

            {{-- 投稿削除ボタン（本人のみ） --}}
            @if (Auth::id() === $post->user_id)
                <form action="{{ route('posts.destroy', $post) }}" method="POST" class="mt-3">
                    @csrf
                    @method('DELETE')
                    <button class="bg-red-500 text-white px-3 py-1 rounded">
                        削除
                    </button>
                </form>
            @endif
        </div>

        {{-- ======================== --}}
        {{-- コメント一覧 --}}
        {{-- ======================== --}}
        <div class="bg-white shadow p-6 rounded-lg mb-6">
            <h3 class="font-bold text-lg mb-4">コメント一覧</h3>

            @forelse ($post->comments as $comment)
                <div class="border-b pb-3 mb-3">
                    <div class="text-gray-700">
                        <strong>{{ $comment->user->name }}</strong>
                        <span class="text-sm text-gray-500">（{{ $comment->created_at->diffForHumans() }}）</span>
                    </div>

                    {{-- ← 修正ポイント：content に変更 --}}
                    <p class="mt-1 whitespace-pre-wrap">{{ $comment->content }}</p>

                    {{-- 削除ボタン（本人のみ） --}}
                    @if (Auth::id() === $comment->user_id)
                        <form action="{{ route('comments.destroy', $comment) }}" method="POST" class="mt-2">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-500 text-sm">削除</button>
                        </form>
                    @endif
                </div>
            @empty
                <p class="text-gray-500">コメントはまだありません。</p>
            @endforelse
        </div>

        {{-- ======================== --}}
        {{-- コメント投稿フォーム --}}
        {{-- ======================== --}}
        @auth
        <div class="bg-white shadow p-6 rounded-lg">
            <h3 class="font-bold text-lg mb-4">コメントを書く</h3>

            <form action="{{ route('comments.store', $post) }}" method="POST">
                @csrf

                <textarea name="comment" rows="3"
                    class="w-full border rounded p-2 mb-3"
                    placeholder="コメントを入力してください">{{ old('comment') }}</textarea>

                @error('comment')
                    <p class="text-red-600 text-sm mb-3">{{ $message }}</p>
                @enderror

                <button class="bg-blue-500 text-white px-4 py-2 rounded">
                    送信
                </button>
            </form>
        </div>
        @endauth

    </div>
</x-app-layout>
