<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            💬
            @php
                /**
                 * チャット相手の決定ロジック
                 * - receiver が存在する → 相手
                 * - receiver が NULL → proposer（自分）
                 */
                if ($exchange->receiver) {
                    $partner = auth()->id() === $exchange->proposer_user_id
                        ? $exchange->receiver
                        : $exchange->proposer;
                } else {
                    $partner = $exchange->proposer;
                }
            @endphp

            {{ $partner->name }}さんとのやり取り
        </h2>
    </x-slot>

    <div class="max-w-3xl mx-auto mt-6 px-3">

        {{-- メッセージ一覧 --}}
        <div
            id="chat-box"
            class="bg-gray-100 p-4 rounded mb-4 h-96 overflow-y-auto space-y-6"
        >
            @forelse ($messages as $msg)
                <div class="flex justify-center">
                    <div class="w-full max-w-2xl">

                        {{-- 送信者名 --}}
                        <p class="text-sm text-gray-600 mb-1 pl-1">
                            {{ $msg->user?->name ?? '不明なユーザー' }}
                        </p>

                        {{-- メッセージ枠 --}}
                        <div class="bg-white px-5 py-4 rounded-lg shadow text-gray-800">
                            <p class="text-base whitespace-pre-wrap">
                                {{ $msg->message }}
                            </p>

                            <div class="flex justify-between items-center mt-2">
                                <p class="text-xs text-gray-500">
                                    {{ $msg->created_at->format('Y/m/d H:i') }}
                                </p>

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
                                            class="text-xs text-gray-400 hover:text-red-500"
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
                <p class="text-center text-gray-500">
                    まだメッセージはありません。
                </p>
            @endforelse
        </div>

        {{-- 送信フォーム --}}
        <form
            method="POST"
            action="{{ route('rooms.send', $room) }}"
            class="flex items-center gap-2"
        >
            @csrf

            <input
                type="text"
                name="message"
                placeholder="メッセージを入力"
                required
                class="flex-1 h-10 border rounded px-4 text-sm focus:outline-none focus:ring focus:border-blue-300"
            >

            <button
                type="submit"
                class="h-10 px-5 bg-gray-700 text-white text-sm rounded hover:bg-gray-800"
            >
                送信
            </button>
        </form>

    </div>

    <script>
        const chatBox = document.getElementById('chat-box');
        chatBox.scrollTop = chatBox.scrollHeight;
    </script>
</x-app-layout>
