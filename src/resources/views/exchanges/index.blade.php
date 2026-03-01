<x-app-layout>

<div class="py-12">
    <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

{{-- パンくず（安全版） --}}
<nav class="text-sm text-gray-700 mb-2">
    <a href="{{ route('dashboard') ?? '#' }}" class="hover:text-blue-600 font-medium">
        ホーム
    </a>
    <span class="mx-2">＞</span>
    <a href="{{ route('exchanges.index') ?? '#' }}" class="hover:text-blue-600 font-medium">
        物々交換
    </a>
</nav>

        {{-- ページタイトル --}}
        <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
            物々交換 一覧
        </h2>

        {{-- フラッシュメッセージ --}}
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

        {{-- ここから下はそのままでOK --}}

        {{-- 新規交換投稿 --}}
        @auth
            <div class="flex justify-end mb-6">
                <a href="{{ route('exchanges.create') }}"
                   class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">
                    新規交換投稿
                </a>
            </div>
        @endauth

        {{-- 交換一覧 --}}
        @forelse($exchanges as $exchange)
            <div class="border rounded-md p-4 mb-6 shadow-sm bg-white">

                {{-- タイトル & 投稿日時 --}}
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-semibold">
                        {{ $exchange->title ?? 'タイトルなし' }}
                    </h3>
                    <span class="text-sm text-gray-500">
                        {{ $exchange->created_at?->format('Y/m/d H:i') }}
                    </span>
                </div>

{{-- 出品者 --}}
<p class="mb-1">
    <strong>出品者:</strong>
    @if($exchange->proposer)
        <a href="{{ route('users.show', $exchange->proposer->id) }}"
           class="text-blue-600 hover:underline">
            {{ $exchange->proposer->name }}
        </a>
    @else
        不明
    @endif
</p>

                {{-- 内容 --}}
                <p class="mb-1">
                    <strong>提供作物:</strong>
                    {{ $exchange->offered_crop_name ?? '未設定' }}
                </p>
                <p class="mb-1">
                    <strong>希望作物:</strong>
                    {{ $exchange->desired_crop_name ?? '未設定' }}
                </p>
                <p class="mb-1">
                    <strong>エリア:</strong>
                    {{ $exchange->area ?? '未設定' }}
                </p>


@if(!empty($exchange->description))
    <p class="mt-2 text-gray-700 whitespace-pre-line">
        {{ $exchange->description }}
    </p>
@endif

                {{-- 画像 --}}
                @if(!empty($exchange->image_path))
                    <div class="mt-3">
                        <img
                            src="{{ asset('storage/' . $exchange->image_path) }}"
                            alt="交換画像"
                            class="w-full max-w-xs rounded border"
                        >
                    </div>
                @endif



{{-- チャット --}}
@auth
    <div class="mt-4">
        <a href="{{ route('rooms.show', $exchange) }}"
           style="display:inline-block;
                  padding:8px 16px;
                  background:#4f46e5;
                  color:#FFF;
                  font-weight:bold;
                  border-radius:6px;">
            出品者にメッセージを送る
        </a>
    </div>
@endauth


                {{-- 承諾 / 拒否（受信者のみ） --}}
                @auth
                    @if(
                        $exchange->receiver_user_id === auth()->id() &&
                        $exchange->status === 'pending'
                    )
                        <div class="flex space-x-2 mt-4">
                            <form method="POST"
                                  action="{{ route('exchanges.updateStatus', ['exchange' => $exchange, 'status' => 'accepted']) }}">
                                @csrf
                                <button class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                    承諾
                                </button>
                            </form>

                            <form method="POST"
                                  action="{{ route('exchanges.updateStatus', ['exchange' => $exchange, 'status' => 'rejected']) }}">
                                @csrf
                                <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                    拒否
                                </button>
                            </form>
                        </div>
                    @endif
                @endauth

                {{-- 削除（投稿者のみ） --}}
                @auth
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
                @endauth

            </div>
        @empty
            <p class="text-center text-gray-500">
                まだ交換投稿はありません。
            </p>
        @endforelse

    </div>
</div>

</x-app-layout>
