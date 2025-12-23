<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800">
            新規投稿
        </h2>
    </x-slot>

    <div class="py-12">
        <!-- 中央寄せ＆最大幅を広げる -->
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-md rounded-lg max-w-5xl mx-auto">

                <form method="POST" action="{{ route('posts.store') }}" enctype="multipart/form-data">
                    @csrf

                    <!-- ▼ タイトル（追加） -->
                    <div class="mb-6">
                        <label for="title" class="block font-medium text-gray-700">タイトル</label>
                        <input type="text" id="title" name="title"
                               class="block w-full border border-gray-300 rounded-md mt-1 
                                      focus:ring focus:ring-blue-300 focus:border-blue-300"
                               value="{{ old('title') }}"
                               placeholder="例：庭で採れたトマトをおすそ分けします"
                               required>
                        @error('title')
                            <div class="text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- コメント -->
                    <div class="mb-6">
                        <label for="content" class="block font-medium text-gray-700">コメント</label>
                        <textarea id="content" name="content" rows="4"
                                  class="block w-full border-gray-300 rounded-md mt-1 
                                         focus:ring focus:ring-blue-300 focus:border-blue-300"
                                  required>{{ old('content') }}</textarea>
                        @error('content')
                            <div class="text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 写真 -->
                    <div class="mb-6">
                        <label for="image" class="block font-medium text-gray-700">写真</label>
                        <input type="file" id="image" name="image"
                               class="block w-full border border-gray-300 rounded-md mt-1 
                                      focus:ring focus:ring-blue-300 focus:border-blue-300" />
                        @error('image')
                            <div class="text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- タグ -->
                    <div class="mb-6">
                        <label for="tags" class="block font-medium text-gray-700">タグ</label>
                        <input type="text" id="tags" name="tags"
                               class="block w-full border border-gray-300 rounded-md mt-1 
                                      focus:ring focus:ring-blue-300 focus:border-blue-300"
                               placeholder="例: トマト ベランダ菜園"
                               value="{{ old('tags') }}">
                        @error('tags')
                            <div class="text-red-500 mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- 送信ボタン -->
                    <div class="mt-8 mb-4 flex justify-center">
                        <button type="submit"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-10 py-3 rounded-md border border-blue-800">
                            投稿する
                        </button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</x-app-layout>
