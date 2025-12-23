<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            チャットルーム
        </h2>
    </x-slot>

```
<div class="max-w-3xl mx-auto py-6">

    {{-- 交換内容 --}}
    <div class="bg-white shadow-md p-4 rounded mb-4">
        <h3 class="text-xl font-bold mb-2">{{ $exchange->title }}</h3>

        {{-- 画像表示 --}}
        @if ($exchange->image_path)
            <div class="mb-3">
                <img
                    src="{{ asset('storage/' . $exchange->image_path) }}"
                    alt="交換画像"
                    class="w-full max-h-96 object-contain rounded border"
                >
            </div>
        @endif

        <p class="text-gray-700 mb-3">
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
            <div>
                <span class="font-semibold">ステータス:</span>
                {{ $exchange->status }}
            </div>
        </div>
    </div>

    {{-- メッセージ一覧 --}}
    <div
        id="chat-box"
        class="bg-gray-100 p-4 rounded mb-4 h-96 overflow-y-auto"
    >
        @forelse ($messages as $msg)
            <div
                class="mb-2 flex {{ $msg->sender_user_id === auth()->id() ? 'justify-end' : 'justify-start' }}"
            >
                <div
                    class="px-3 py-2 rounded-lg max-w-[70%] shadow
                    {{ $msg->sender_user_id === auth()->id()
                        ? 'bg-blue-500 text-white'
                        : 'bg-white text-gray-800' }}"
                >
                    {{-- 送信者 --}}
                    <strong class="block text-xs mb-1">
                        {{ $msg->sender?->name ?? '不明なユーザー' }}
                    </strong>

                    {{-- メッセージ内容 --}}
                    <p class="text-sm whitespace-pre-wrap">
                        {{ $msg->message }}
                    </p>

                    {{-- 日時 --}}
                    <small class="block text-right text-xs text-gray-400 mt-1">
                        {{ $msg->created_at->format('Y/m/d H:i') }}
                    </small>
                </div>
            </div>
        @empty
            <p class="text-center text-gray-500">
                まだメッセージはありません。
            </p>
        @endforelse
    </div>

    {{-- メッセージ送信フォーム --}}
    <form
        method="POST"
        action="{{ route('rooms.send', $room->id) }}"
        class="flex space-x-2"
    >
        @csrf

        <input
            type="text"
            name="message"
            class="flex-1 border rounded px-3 py-2"
            placeholder="メッセージを入力..."
            required
        >

        <button
            type="submit"
            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"
        >
            送信
        </button>
    </form>

</div>
```

</x-app-layout>
