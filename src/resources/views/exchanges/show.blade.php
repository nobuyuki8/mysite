<x-app-layout>
<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800">
        {{ $exchange->title }}
    </h2>
</x-slot>

<div class="max-w-3xl mx-auto py-6">

    {{-- フラッシュメッセージ --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 text-green-800 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- 交換内容 --}}
    <div class="bg-white shadow-md p-4 rounded mb-4">
        <h3 class="text-xl font-bold mb-2">{{ $exchange->title }}</h3>

        {{-- ステータス表示 --}}
        <div class="mb-3">
            <span class="font-semibold">状態:</span>
            @if($exchange->status === 'completed')
                <span class="text-green-600 font-bold">取引完了</span>
            @elseif($exchange->status === 'accepted')
                <span class="text-blue-600 font-bold">取引中</span>
            @else
                <span class="text-gray-600">募集中</span>
            @endif
        </div>

        {{-- 画像 --}}
        @if ($exchange->image_path)
            <div class="mb-3">
                <img
                    src="{{ asset('storage/' . $exchange->image_path) }}"
                    alt="交換画像"
                    class="w-full max-h-96 object-contain rounded border"
                >
            </div>
        @endif

        <p class="text-gray-700 mb-3 whitespace-pre-line">
            {{ $exchange->description }}
        </p>

        <div class="text-sm text-gray-600 space-y-1">
            <div>
                <span class="font-semibold">出品者:</span>
                {{ $exchange->proposer?->name ?? '不明' }}
            </div>
            <div>
                <span class="font-semibold">提供作物:</span>
                {{ $exchange->offered_crop_name }}
            </div>
            <div>
                <span class="font-semibold">希望作物:</span>
                {{ $exchange->desired_crop_name }}
            </div>
            <div>
                <span class="font-semibold">エリア:</span>
                {{ $exchange->area ?? '未設定' }}
            </div>
        </div>

        {{-- 🔥 取引完了ボタン（出品者のみ表示） --}}
        @auth
            @if(
                auth()->id() === $exchange->proposer_user_id &&
                $exchange->status !== 'completed'
            )
                <div class="mt-4 text-right">
                    <form method="POST"
                          action="{{ route('exchanges.complete', $exchange) }}"
                          onsubmit="return confirm('本当に取引完了にしますか？');">
                        @csrf
                        <button
                            type="submit"
                            class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                            取引完了
                        </button>
                    </form>
                </div>
            @endif
        @endauth

    </div>

    {{-- メッセージ一覧 --}}
    <div id="chat-box"
         class="bg-gray-100 p-4 rounded mb-4 h-96 overflow-y-auto">

        @if ($room && $messages->isNotEmpty())
            @foreach ($messages as $msg)
                <div class="mb-2 flex {{ $msg->sender_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                    <div class="px-3 py-2 rounded-lg max-w-[70%] shadow
                        {{ $msg->sender_user_id === auth()->id()
                            ? 'bg-blue-500 text-white'
                            : 'bg-white text-gray-800' }}">

                        <strong class="block text-xs mb-1">
                            {{ $msg->sender?->name ?? '不明なユーザー' }}
                        </strong>

                        <p class="text-sm whitespace-pre-line">
                            {{ $msg->message }}
                        </p>

                        <small class="block text-right text-xs text-gray-400 mt-1">
                            {{ $msg->created_at->format('Y/m/d H:i') }}
                        </small>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-gray-500">
                まだメッセージはありません。
            </p>
        @endif
    </div>

    {{-- メッセージ送信フォーム --}}
    @auth
        @if ($room && $exchange->status !== 'completed')
            <form method="POST"
                  action="{{ route('rooms.send', $room->id) }}"
                  class="flex space-x-2">
                @csrf

                <input type="text"
                       name="message"
                       class="flex-1 border rounded px-3 py-2"
                       placeholder="メッセージを入力..."
                       required>

                <button type="submit"
                        class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                    送信
                </button>
            </form>
        @elseif($exchange->status === 'completed')
            <div class="text-center text-gray-500 mt-4">
                この取引は完了しました。
            </div>
        @endif
    @else
        <div class="text-center text-sm text-gray-500 mt-4">
            メッセージを送信するにはログインしてください
        </div>
    @endauth

</div>

<script>
    const chatBox = document.getElementById('chat-box');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
</script>

</x-app-layout>