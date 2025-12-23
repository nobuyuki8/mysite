<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800">
            {{ $user->name }} さんのマイページ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10">

            <!-- ================= プロフィール ================= -->
            <div class="bg-white p-10 rounded-xl shadow-lg">

                <div class="flex flex-col sm:flex-row items-center gap-8">

                    {{-- プロフィール画像 --}}
                    @if ($user->profile_image)
                        <img
                            src="{{ asset('storage/' . $user->profile_image) }}"
                            alt="プロフィール画像"
                            class="w-40 h-40 rounded-full object-cover border"
                        >
                    @else
                        <div
                            class="w-40 h-40 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 text-xl">
                            N/A
                        </div>
                    @endif

                    {{-- プロフィール情報 --}}
                    <div class="flex-1 text-center sm:text-left">
                        <p class="text-3xl font-bold text-gray-800">
                            {{ $user->name }}
                        </p>

                        @if ($user->area)
                            <p class="text-lg text-gray-600 mt-2">
                                📍 エリア：{{ $user->area }}
                            </p>
                        @endif

                        @if ($user->gardening_experience)
                            <p class="text-lg text-gray-600">
                                🌱 経験：{{ $user->gardening_experience }}
                            </p>
                        @endif

                        @if ($user->favorite_crop)
                            <p class="text-lg text-gray-600">
                                🥕 好きな作物：{{ $user->favorite_crop }}
                            </p>
                        @endif
                    </div>
                </div>

                {{-- 自己紹介 --}}
                @if ($user->introduction)
                    <div class="mt-8">
                        <h3 class="text-xl font-semibold mb-3">自己紹介</h3>
                        <p class="text-lg text-gray-700 leading-relaxed">
                            {{ $user->introduction }}
                        </p>
                    </div>
                @endif
            </div>

            <!-- ================= 通常投稿一覧 ================= -->
            <div class="bg-white p-8 rounded-xl shadow-md">
                <h3 class="font-semibold text-2xl mb-6">投稿一覧</h3>

                <div class="space-y-6">
                    @forelse ($posts as $post)
                        <a
                            href="{{ route('posts.show', $post) }}"
                            class="block p-6 bg-gray-50 rounded-lg shadow-sm hover:bg-gray-100 transition"
                        >
                            <p class="text-lg text-gray-800">
                                {{ $post->content }}
                            </p>

                            @if ($post->image)
                                <img
                                    src="{{ asset('storage/' . $post->image) }}"
                                    alt="投稿画像"
                                    class="w-full mt-4 rounded-lg"
                                >
                            @endif

                            @if ($post->tags->count())
                                <div class="flex flex-wrap gap-2 mt-4">
                                    @foreach ($post->tags as $tag)
                                        <span
                                            class="bg-green-100 text-green-800 text-sm px-3 py-1 rounded-full">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif

                            <p class="text-gray-500 text-sm mt-3">
                                投稿日：{{ $post->created_at->format('Y/m/d H:i') }}
                            </p>
                        </a>
                    @empty
                        <p class="text-gray-500 text-center text-lg">
                            まだ投稿はありません。
                        </p>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            </div>

            <!-- ================= 物々交換投稿一覧 ================= -->
            <div class="bg-white p-8 rounded-xl shadow-md">
                <h3 class="font-semibold text-2xl mb-6">物々交換の投稿</h3>

                <div class="space-y-6">
                    @forelse ($exchanges as $exchange)
                        <a
                            href="{{ route('exchanges.show', $exchange) }}"
                            class="block p-6 bg-gray-50 rounded-lg shadow-sm hover:bg-gray-100 transition"
                        >
                            <p class="text-lg font-semibold text-gray-800">
                                {{ $exchange->title }}
                            </p>

                            <p class="text-gray-700 mt-2">
                                {{ Str::limit($exchange->description, 120) }}
                            </p>

                            @if ($exchange->image)
                                <img
                                    src="{{ asset('storage/' . $exchange->image) }}"
                                    alt="物々交換画像"
                                    class="w-full mt-4 rounded-lg"
                                >
                            @endif

                            <p class="text-gray-500 text-sm mt-3">
                                投稿日：{{ $exchange->created_at->format('Y/m/d H:i') }}
                            </p>
                        </a>
                    @empty
                        <p class="text-gray-500 text-center text-lg">
                            まだ物々交換の投稿はありません。
                        </p>
                    @endforelse
                </div>

                <div class="mt-8">
                    {{ $exchanges->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
