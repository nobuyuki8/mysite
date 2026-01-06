@extends('layouts.app')

{{-- ページ見出し --}}
@section('header')
    <h2 class="font-semibold text-xl text-gray-800">
        新規投稿
    </h2>
@endsection

@section('content')

<div class="py-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white p-8 shadow-md rounded-lg max-w-5xl mx-auto">

            {{-- バリデーションエラー --}}
            @if ($errors->any())
                <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST"
                  action="{{ route('posts.store') }}"
                  enctype="multipart/form-data"
                  class="space-y-6">
                @csrf

                {{-- タイトル（★必須・保存対象） --}}
                <div>
                    <label for="title" class="block font-medium text-gray-700">
                        タイトル <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="title"
                        name="title"
                        value="{{ old('title') }}"
                        required
                        placeholder="例：庭で採れたトマトをおすそ分けします"
                        class="block w-full border border-gray-300 rounded-md mt-1 px-3 py-2
                               focus:ring focus:ring-blue-300 focus:border-blue-300"
                    >
                </div>

                {{-- コメント --}}
                <div>
                    <label for="content" class="block font-medium text-gray-700">
                        コメント <span class="text-red-500">*</span>
                    </label>
                    <textarea
                        id="content"
                        name="content"
                        rows="5"
                        required
                        class="block w-full border border-gray-300 rounded-md mt-1 px-3 py-2
                               focus:ring focus:ring-blue-300 focus:border-blue-300"
                    >{{ old('content') }}</textarea>
                </div>

                {{-- 写真 --}}
                <div>
                    <label for="image" class="block font-medium text-gray-700">
                        写真（任意）
                    </label>
                    <input
                        type="file"
                        id="image"
                        name="image"
                        accept="image/*"
                        class="block w-full border border-gray-300 rounded-md mt-1 px-3 py-2"
                    >
                </div>

                {{-- タグ --}}
                <div>
                    <label for="tags" class="block font-medium text-gray-700">
                        タグ（スペース or カンマ区切り）
                    </label>
                    <input
                        type="text"
                        id="tags"
                        name="tags"
                        value="{{ old('tags') }}"
                        placeholder="例：トマト, ベランダ菜園"
                        class="block w-full border border-gray-300 rounded-md mt-1 px-3 py-2"
                    >
                </div>

                {{-- ボタン --}}
                <div class="pt-6 flex justify-center gap-4">
                    <a href="{{ route('posts.index') }}"
                       class="px-6 py-2 border rounded-md text-gray-600 hover:bg-gray-50">
                        戻る
                    </a>

                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-2 rounded-md">
                        投稿する
                    </button>
                </div>

            </form>

        </div>
    </div>
</div>

@endsection
