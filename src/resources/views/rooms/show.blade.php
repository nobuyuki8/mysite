<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        💬 {{ $exchange->title }} の出品者にメッセージを送る
    </h2>
</x-slot>

    <div class="max-w-3xl mx-auto mt-6 px-3">

        {{-- 交換情報 --}}
        <div class="bg-white shadow rounded p-4 mb-4">
            <h3 class="font-bold text-lg mb-2">{{ $exchange->title }}</h3>
            <p class="text-gray-700 text-sm whitespace-pre-wrap">
                {{ $exchange->description }}
            </p>

            <!-- <p class="text-xs text-gray-500 mt-2">
                ステータス：{{ $exchange->status }}
            </p> -->
        </div>

        {{-- メッセージ一覧 --}}
        <div
            id="chat-box"
            class="bg-gray-100 p-4 rounded mb-4 h-96 overflow-y-auto space-y-6"
        >
            @forelse ($messages as $msg)
                <div class="flex {{ $msg->user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="w-full max-w-2xl">

                        {{-- 送信者名 --}}
                        <p class="text-xs text-gray-600 mb-1">
                            {{ $msg->user?->name ?? '不明なユーザー' }}
                        </p>

                        {{-- メッセージ --}}
                        <div
                            class="px-5 py-3 rounded-lg shadow
                            {{ $msg->user_id === auth()->id()
                                ? 'bg-blue-500 text-white'
                                : 'bg-white text-gray-800' }} "
                        >
                            <p class="text-sm whitespace-pre-wrap">
                                {{ $msg->message }}
                            </p>

                            <div class="flex justify-between items-center mt-2">
                                {{-- タイムスタンプを日本時間に --}}
                                <span class="text-xs opacity-70">
                                    {{ $msg->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
                                </span>

                                {{-- 自分のメッセージのみ削除 --}}
                                @if ($msg->user_id === auth()->id())
                                    <form
                                        method="POST"
                                        action="{{ route('messages.destroy', $msg) }}"
                                        onsubmit="return confirm('このメッセージを削除しますか？');"
                                    >
                                        @csrf
                                        @method('DELETE')
                                        <button
                                            type="submit"
                                            class="text-xs hover:text-red-300"
                                        >
                                            削除
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-center text-gray-500 text-sm">
                    まだメッセージはありません。
                </p>
            @endforelse
        </div>

        {{-- 送信フォーム：ログインユーザーのみ --}}
        @auth
            <form
                method="POST"
                action="{{ route('rooms.send', $room->id) }}"
                class="flex items-center gap-2"
            >
                @csrf

                <input
                    type="text"
                    name="message"
                    placeholder="メッセージを入力"
                    required
                    class="flex-1 h-10 border rounded px-4 text-sm
                           focus:outline-none focus:ring focus:border-blue-300"
                >

                <button
                    type="submit"
                    class="h-10 px-5 bg-gray-700 text-white text-sm rounded
                           hover:bg-gray-800"
                >
                    送信
                </button>
            </form>
        @else
            <p class="text-center text-sm text-gray-500">
                メッセージを送信するにはログインしてください。
            </p>
        @endauth

    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        if (chatBox) {
            chatBox.scrollTop = chatBox.scrollHeight;
        }
    </script>
</x-app-layout>
