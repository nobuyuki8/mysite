<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        💬 {{ $exchange->title }} の出品者にメッセージを送る
    </h2>
</x-slot>

<div class="max-w-3xl mx-auto mt-6 px-3">

@if(session('status'))
<div class="bg-green-100 text-green-800 p-3 rounded mb-4">
    {{ session('status') }}
</div>
@endif

    {{-- 交換情報 --}}
    <div class="bg-white shadow rounded p-4 mb-4">

        <h3 class="font-bold text-lg mb-2">{{ $exchange->title }}</h3>

        <p class="text-gray-700 text-sm whitespace-pre-line">
            {{ trim($exchange->description) }}
        </p>

        {{-- 取引ステータス --}}
        <p class="text-xs text-gray-500 mt-2">
            ステータス：
            @if($exchange->status === 'completed')
                <span class="text-green-600 font-bold">取引完了</span>
            @else
                <span class="text-yellow-600">取引中</span>
            @endif
        </p>

        {{-- 取引完了ボタン（出品者のみ） --}}
        @auth
        @if(auth()->id() === $exchange->proposer_user_id && $exchange->status !== 'completed')

        <div class="mt-4 text-right">
            <form
                method="POST"
                action="{{ route('exchanges.complete', $exchange) }}"
                onsubmit="return confirm('取引を完了しますか？');"
            >
                @csrf

                <button
                    type="submit"
                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600"
                >
                    取引完了
                </button>
            </form>
        </div>

        @endif
        @endauth

    </div>

    {{-- メッセージ一覧 --}}
    <div
        id="chat-box"
        class="bg-gray-100 p-4 rounded mb-4 h-96 overflow-y-auto space-y-2"
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
                        : 'bg-white text-gray-800' }}"
                >

                    <p class="text-sm whitespace-pre-wrap">
                        {{ $msg->message }}
                    </p>

                    <div class="flex justify-between items-center mt-2">

                        {{-- 時間 --}}
                        <span class="text-xs opacity-70">
                            {{ $msg->created_at->timezone('Asia/Tokyo')->format('Y/m/d H:i') }}
                        </span>

                        {{-- 自分のメッセージ削除 --}}
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

    {{-- メッセージ送信 --}}
    @auth

        @if($exchange->status !== 'completed')

        <form
            method="POST"
            action="{{ route('rooms.send', $room->id) }}"
            class="flex items-end gap-2"
        >

            @csrf

            <textarea
                name="message"
                rows="3"
                placeholder="メッセージを入力"
                required
                class="flex-1 border rounded px-4 py-2 text-sm
                       focus:outline-none focus:ring focus:border-blue-300 resize-none"
            ></textarea>

            <button
                type="submit"
                class="h-10 px-5 bg-gray-700 text-white text-sm rounded
                       hover:bg-gray-800"
            >
                送信
            </button>

        </form>

        @else

        <p class="text-center text-green-600 font-semibold">
            この取引は完了しました
        </p>

        @endif

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