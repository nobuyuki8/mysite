<x-app-layout>

{{-- ================= ページ見出し ================= --}}
<x-slot name="header">
<div class="flex justify-between items-center">
    <h2 class="font-semibold text-xl text-gray-800">
        投稿一覧
    </h2>

    @auth
        <a href="{{ route('posts.create') }}"
           class="inline-flex items-center px-5 py-2.5
                  bg-blue-600 text-white font-semibold
                  rounded-md shadow
                  hover:bg-blue-700 transition">
            ＋ 新規投稿
        </a>
    @endauth
</div>
</x-slot>

{{-- ================= メインコンテンツ ================= --}}
<div class="py-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="max-w-5xl mx-auto space-y-6">

            {{-- 検索フォーム --}}
            <form method="GET"
                  action="{{ route('posts.index') }}"
                  class="flex flex-wrap items-center gap-3 bg-white p-4 rounded shadow-sm">

                <input type="text" name="crop" placeholder="作物名"
                       value="{{ request('crop') }}"
                       class="flex-grow min-w-[140px] border rounded-md px-2 py-1 text-sm">

                @php
                    $tokyo23 = [
                        '千代田区','中央区','港区','新宿区','文京区','台東区','墨田区','江東区',
                        '品川区','目黒区','大田区','世田谷区','渋谷区','中野区','杉並区','豊島区',
                        '北区','荒川区','板橋区','練馬区','足立区','葛飾区','江戸川区'
                    ];
                @endphp

                <select name="area"
                        class="flex-grow min-w-[140px] border rounded-md px-2 py-1 text-sm">
                    <option value="">エリア</option>
                    @foreach ($tokyo23 as $area)
                        <option value="{{ $area }}" {{ request('area') === $area ? 'selected' : '' }}>
                            {{ $area }}
                        </option>
                    @endforeach
                    <option value="outside23" {{ request('area') === 'outside23' ? 'selected' : '' }}>
                        23区外
                    </option>
                </select>

                <input type="text" name="tag" placeholder="タグ"
                       value="{{ request('tag') }}"
                       class="flex-grow min-w-[140px] border rounded-md px-2 py-1 text-sm">

                <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-1.5 rounded-md text-sm transition">
                    検索
                </button>
            </form>

            {{-- 投稿一覧 --}}
            @forelse ($posts as $post)
                <div class="bg-white p-4 shadow-sm rounded-lg">

                    <a href="{{ route('posts.show', $post) }}"
                       class="block hover:bg-gray-50 transition">

                        {{-- タイトル --}}
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">
                            {{ $post->title ?? '（タイトルなし）' }}
                        </h3>

                        {{-- 本文 --}}
                        <div class="text-sm text-gray-700 whitespace-pre-wrap">
                            {{ $post->content }}
                        </div>

                        {{-- 画像 --}}
                        @if ($post->image)
                            <img src="{{ asset('storage/' . $post->image) }}"
                                 class="w-full rounded-md mt-3 max-h-48 object-cover">
                        @endif

                        {{-- タグ --}}
                        @if ($post->tags->count())
                            <div class="flex flex-wrap gap-1 mt-3">
                                @foreach ($post->tags as $tag)
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">
                                        {{ $tag->name }}
                                    </span>
                                @endforeach
                            </div>
                        @endif

                        {{-- 投稿者情報 --}}
                        <div class="mt-3 text-xs text-gray-500">
                            投稿者：
                            @if($post->user)
                                <a href="{{ route('users.show', $post->user->id) }}"
                                   class="text-blue-600 hover:underline">
                                    {{ $post->user->name }}
                                </a>
                            @else
                                （不明）
                            @endif
                            ｜ {{ $post->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
                        </div>
                    </a>

                    <div class="mt-3 flex justify-between items-center text-sm">

                        {{-- いいね --}}
                        @auth
                            <button type="button"
                                    class="like-btn bg-pink-100 text-pink-600 px-3 py-1 rounded-md hover:bg-pink-200 transition"
                                    data-post-id="{{ $post->id }}">
                                ❤ いいね (<span class="like-count">{{ $post->likers->count() }}</span>)
                            </button>
                        @endauth

                        {{-- 削除 --}}
                        @if (Auth::id() === $post->user_id)
                            <form method="POST" action="{{ route('posts.destroy', $post) }}">
                                @csrf
                                @method('DELETE')
                                <button class="bg-red-100 text-red-600 px-3 py-1 rounded-md hover:bg-red-200 transition">
                                    削除
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    <p class="text-lg">投稿はまだありません。</p>
                    <p class="text-sm mt-2">最初の投稿をしてみましょう 🌱</p>
                </div>
            @endforelse

            {{-- ページネーション --}}
            {{ $posts->withQueryString()->links() }}

        </div>
    </div>
</div>
</x-app-layout>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.like-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            fetch(`/posts/${btn.dataset.postId}/like`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
                        .content
                }
            })
            .then(res => res.json())
            .then(data => {
                btn.querySelector('.like-count').textContent = data.count;
            });
        });
    });
});
</script>
@endpush
