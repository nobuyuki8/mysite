<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            投稿一覧
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto space-y-4">

                <!-- 検索フォーム -->
                <form method="GET" action="{{ route('posts.index') }}" class="mb-4 flex flex-wrap items-center gap-3">
                    <input type="text" name="crop" placeholder="作物名"
                           value="{{ request('crop') }}"
                           class="flex-grow min-w-[140px] border border-gray-300 rounded-md px-2 py-1 focus:ring focus:ring-blue-300 text-sm">

                    @php
                        $tokyo23 = [
                            '千代田区','中央区','港区','新宿区','文京区','台東区','墨田区','江東区',
                            '品川区','目黒区','大田区','世田谷区','渋谷区','中野区','杉並区','豊島区',
                            '北区','荒川区','板橋区','練馬区','足立区','葛飾区','江戸川区'
                        ];
                    @endphp

                    <select name="area"
                            class="flex-grow min-w-[140px] border border-gray-300 rounded-md px-2 py-1 focus:ring focus:ring-blue-300 text-sm">
                        <option value="">エリア</option>
                        @foreach ($tokyo23 as $area)
                            <option value="{{ $area }}" {{ request('area') === $area ? 'selected' : '' }}>{{ $area }}</option>
                        @endforeach
                        <option value="outside23" {{ request('area') === 'outside23' ? 'selected' : '' }}>23区外</option>
                    </select>

                    <input type="text" name="tag" placeholder="タグ"
                           value="{{ request('tag') }}"
                           class="flex-grow min-w-[140px] border border-gray-300 rounded-md px-2 py-1 focus:ring focus:ring-blue-300 text-sm">

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md text-sm">
                        検索
                    </button>
                </form>

                <!-- 投稿一覧 -->
                @forelse ($posts as $post)
                    <div class="bg-white p-4 shadow-sm rounded-lg">

                        <!-- 🔗 投稿詳細ページリンク -->
                        <a href="{{ route('posts.show', $post) }}" class="block hover:bg-gray-50 transition">

                            <div class="mb-1 text-gray-700 text-sm">{{ $post->content }}</div>

                            @if ($post->image)
                                <img src="{{ asset('storage/' . $post->image) }}" alt="投稿画像"
                                     class="w-full rounded-md mb-1 max-h-48 object-cover">
                            @endif

                            @if ($post->tags->count())
                                <div class="flex flex-wrap gap-1 mt-1">
                                    @foreach ($post->tags as $tag)
                                        <span class="bg-green-100 text-green-800 text-xs px-2 py-0.5 rounded">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <!-- 投稿者名リンク化 -->
                            <div class="mt-2 text-xs text-gray-500">
                                投稿者: 
                                <a href="{{ route('users.show', $post->user) }}" class="text-blue-600 hover:underline">
                                    {{ $post->user->name }}
                                </a>
                                |
                                投稿日: {{ $post->created_at->format('Y/m/d H:i') }}
                            </div>
                        </a>

                        <!-- ❤️いいね（左）｜ 🗑削除（右） -->
                        <div class="mt-2 flex justify-between items-center text-sm">

                            <!-- 左側（いいね） -->
                            <div>
                                @auth
                                    <button class="like-btn text-pink-500 hover:text-pink-700"
                                            data-post-id="{{ $post->id }}">
                                        ❤ いいね (<span class="like-count">{{ $post->likers->count() }}</span>)
                                    </button>
                                @endauth
                            </div>

                            <!-- 右側（削除） -->
                            <div>
                                @if (Auth::id() === $post->user_id)
                                    <form method="POST"
                                          action="{{ route('posts.destroy', $post->id) }}"
                                          class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-500 hover:text-red-700">
                                            削除
                                        </button>
                                    </form>
                                @endif
                            </div>

                        </div>
                    </div>
                @empty
                    <p class="text-gray-500 text-center">投稿はまだありません。</p>
                @endforelse

                <div class="mt-4">
                    {{ $posts->withQueryString()->links() }}
                </div>

            </div>
        </div>
    </div>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const buttons = document.querySelectorAll('.like-btn');
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    const postId = this.dataset.postId;
                    const countSpan = this.querySelector('.like-count');
                    const btn = this;

                    fetch(`/posts/${postId}/like`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json'
                        },
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.status === 'success') {
                            countSpan.textContent = data.count;
                            if (data.liked) {
                                btn.classList.add('text-pink-500');
                                btn.classList.remove('text-gray-400');
                            } else {
                                btn.classList.remove('text-pink-500');
                                btn.classList.add('text-gray-400');
                            }
                        }
                    });
                });
            });
        });
    </script>

</x-app-layout>
