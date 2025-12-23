<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('物々交換 一覧') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- 成功メッセージ --}}
            @if(session('status'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-800 rounded">
                    {{ session('success') }}
                </div>
            @endif

            {{-- 新規交換投稿 --}}
            <div class="flex justify-end mb-6">
                <a href="{{ route('exchanges.create') }}"
                   class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    新規交換投稿
                </a>
            </div>

            {{-- 交換一覧 --}}
            @forelse($exchanges as $exchange)
                <div class="border rounded-md p-4 mb-4 shadow-sm bg-white">

                    {{-- タイトル --}}
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg font-semibold">{{ $exchange->title }}</h3>
                        <span class="text-sm text-gray-500">
                            {{ $exchange->created_at->format('Y/m/d H:i') }}
                        </span>
                    </div>

                    {{-- ★ 出品者（プロフィールリンク） --}}
                    <p class="mb-1">
                        <strong>出品者:</strong>
                        @if ($exchange->proposer)
                            <a href="{{ route('users.show', $exchange->proposer) }}"
                               class="text-blue-600 hover:underline">
                                {{ $exchange->proposer->name }}
                            </a>
                        @else
                            <span class="text-gray-400">不明</span>
                        @endif
                    </p>

                    <p class="mb-1"><strong>提供作物:</strong> {{ $exchange->offered_crop_name }}</p>
                    <p class="mb-1"><strong>希望作物:</strong> {{ $exchange->desired_crop_name }}</p>
                    <p class="mb-1"><strong>エリア:</strong> {{ $exchange->area }}</p>
                    <p class="mb-2">{{ $exchange->description }}</p>

                    {{-- チャットボタン --}}
                    <div class="mt-4">
                        <a href="{{ route('rooms.show', $exchange) }}"
                           class="inline-block px-4 py-2 bg-indigo-500 text-white rounded hover:bg-indigo-600">
                            💬 チャットへ
                        </a>
                    </div>

                    {{-- 承諾 / 拒否（受信者のみ） --}}
                    @if($exchange->receiver_user_id === auth()->id() && $exchange->status === 'pending')
                        <div class="flex space-x-2 mt-4">
                            <form method="POST"
                                  action="{{ route('exchanges.updateStatus', ['exchange' => $exchange, 'status' => 'accepted']) }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                    承諾
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('exchanges.updateStatus', ['exchange' => $exchange, 'status' => 'rejected']) }}">
                                @csrf
                                <button type="submit"
                                        class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    拒否
                                </button>
                            </form>
                        </div>
                    @endif

                    {{-- 削除（投稿者本人のみ） --}}
                    @if($exchange->proposer_user_id === auth()->id())
                        <div class="mt-4 flex justify-end">
                            <form action="{{ route('exchanges.destroy', $exchange) }}"
                                  method="POST"
                                  onsubmit="return confirm('本当に削除しますか？');">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-600 text-white rounded hover:bg-red-700">
                                    🗑️ 削除
                                </button>
                            </form>
                        </div>
                    @endif

                </div>
            @empty
                <p class="text-center text-gray-500">まだ交換投稿はありません。</p>
            @endforelse

        </div>
    </div>
</x-app-layout>
