@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800">
        プロフィール編集
    </h2>
@endsection

@section('content')

    {{-- 更新成功メッセージ --}}
    @if (session('status') === 'profile-updated')
        <div class="bg-green-100 text-green-700 px-4 py-2 rounded mb-4">
            プロフィールを更新しました。
        </div>
    @endif

    {{-- エラーメッセージ --}}
    @if ($errors->any())
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4">
            <ul class="list-disc ml-4">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        {{-- 名前 --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">名前</label>
            <input type="text" name="name"
                   class="w-full border rounded px-3 py-2"
                   value="{{ old('name', auth()->user()->name) }}">
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">メールアドレス</label>
            <input type="email" name="email"
                    class="w-full border rounded px-3 py-2"
                    value="{{ old('email', auth()->user()->email) }}" required>
        </div>

        {{-- エリア --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">エリア</label>
            <select name="area" class="w-full border rounded px-3 py-2">
                @foreach ([
                    '千代田区','中央区','港区','新宿区','文京区','台東区','墨田区','江東区',
                    '品川区','目黒区','大田区','世田谷区','渋谷区','中野区','杉並区','豊島区',
                    '北区','荒川区','板橋区','練馬区','足立区','葛飾区','江戸川区'
                ] as $ward)
                    <option value="{{ $ward }}"
                        {{ old('area', auth()->user()->area) === $ward ? 'selected' : '' }}>
                        {{ $ward }}
                    </option>
                @endforeach
                <option value="東京23区外" {{ old('area', auth()->user()->area) === '東京23区外' ? 'selected' : '' }}>東京23区外</option>
                <option value="その他" {{ old('area', auth()->user()->area) === 'その他' ? 'selected' : '' }}>その他</option>
            </select>
        </div>

        {{-- プロフィール画像 --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">プロフィール画像</label>

            @if (auth()->user()->profile_image)
                <img src="{{ asset('storage/' . auth()->user()->profile_image) }}"
                     class="w-20 h-20 rounded-full mb-2">
            @endif

            <input type="file" name="profile_image">
        </div>

        {{-- 紹介文 --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">紹介文</label>
            <textarea name="introduction" rows="4"
                      class="w-full border rounded px-3 py-2">{{ old('introduction', auth()->user()->introduction) }}</textarea>
        </div>

        {{-- 好きな農作物 --}}
        <div class="mb-4">
            <label class="block text-sm font-medium mb-1">好きな農作物</label>
            <input type="text" name="favorite_crop"
                   class="w-full border rounded px-3 py-2"
                   value="{{ old('favorite_crop', auth()->user()->favorite_crop) }}">
        </div>

        {{-- 保存ボタン --}}
        <div class="mt-4">
            <button type="submit"
                    class="save-button w-full text-lg font-medium px-6 py-3 rounded-lg shadow-md">
                保存する
            </button>
        </div>

    </form>

@endsection

<style>
.save-button {
    background-color: #1d4ed8;
    color: white;
    transition: background-color 0.3s;
}
.save-button:hover {
    background-color: #2563eb;
}
</style>
