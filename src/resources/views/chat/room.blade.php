<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            チャットルーム
        </h2>
    </x-slot>

    <div class="max-w-xl mx-auto py-6">

        {{-- メッセージ一覧 --}}
        <div class="bg-white shadow rounded p-4 h-96 overflow-y-scroll mb-4">

            @foreach ($room->messages as $msg)
                <div class="mb-4 
                    @if($msg->user_id === Auth::id()) text-right @endif">

                    {{-- メッセージの枠 --}}
                    <div class="
                        inline-block px-3 py-2 rounded-lg
                        @if($msg->user_id === Auth::id())
                            bg-blue-500 text-white
                        @else
                            bg-gray-200
                        @endif
                    ">
                        {{ $msg->content }}
                    </div>

                    {{-- 時間 --}}
                    <div class="text-gray-500 text-xs mt-1">
                        {{ $msg->created_at->format('Y/m/d H:i') }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- メッセージ送信フォーム --}}
        <form action="{{ route('chat.send', $room->id) }}" method="POST">
            @csrf

            <textarea name="message" 
                      class="w-full border rounded p-2 focus:ring focus:ring-blue-200"
                      rows="2"
                      placeholder="メッセージを入力..."></textarea>

            <button type="submit"
                class="mt-2 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 w-full">
                送信
            </button>
        </form>

    </div>
</x-app-layout>
