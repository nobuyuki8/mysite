<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('物々交換 募集フォーム') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-8 shadow-sm sm:rounded-lg">
                {{-- CSRF トークン --}}
                <form method="POST" action="{{ route('exchanges.store') }}" enctype="multipart/form-data">
                    @csrf

                    {{-- タイトル --}}
                    <div class="mb-4">
                        <x-input-label for="title" :value="__('タイトル')" />
                        <x-text-input id="title" name="title" type="text"
                            class="mt-1 block w-full" :value="old('title')" required autofocus />
                        <x-input-error :messages="$errors->get('title')" class="mt-2" />
                    </div>

                    {{-- 画像 --}}
                    <div class="mb-4">
                        <x-input-label for="image" :value="__('画像 (任意)')" />
                        <input id="image" name="image" type="file" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        <x-input-error :messages="$errors->get('image')" class="mt-2" />
                    </div>

                    {{-- 提供する作物 --}}
                    <div class="mb-4">
                        <x-input-label for="offered_crop_name" :value="__('【提供】できる作物・物')" />
                        <x-text-input id="offered_crop_name" name="offered_crop_name" type="text"
                            class="mt-1 block w-full" :value="old('offered_crop_name')"
                            placeholder="例: バジルの苗、ミニトマト (5個)" required />
                        <x-input-error :messages="$errors->get('offered_crop_name')" class="mt-2" />
                    </div>

                    {{-- 希望する作物 --}}
                    <div class="mb-4">
                        <x-input-label for="desired_crop_name" :value="__('【希望】する作物・物')" />
                        <x-text-input id="desired_crop_name" name="desired_crop_name" type="text"
                            class="mt-1 block w-full" :value="old('desired_crop_name')"
                            placeholder="例: ナスの苗、唐辛子" required />
                        <x-input-error :messages="$errors->get('desired_crop_name')" class="mt-2" />
                    </div>

                    {{-- 詳細説明 --}}
                    <div class="mb-4">
                        <x-input-label for="description" :value="__('詳細な説明 (500字まで)')" />
                        <textarea id="description" name="description" rows="4"
                            class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm mt-1"
                            required>{{ old('description') }}</textarea>
                        <x-input-error :messages="$errors->get('description')" class="mt-2" />
                    </div>

                    {{-- 交換希望エリア --}}
                    <div class="mb-6">
                        <x-input-label for="area" :value="__('交換希望エリア (東京の区)')" />
                        <x-text-input id="area" name="area" type="text"
                            class="mt-1 block w-full" :value="old('area')"
                            placeholder="例: 世田谷区、杉並区 (空欄の場合はプロフィール設定値)" />
                        <x-input-error :messages="$errors->get('area')" class="mt-2" />
                    </div>

                    {{-- 送信ボタン --}}
                    <div class="flex items-center justify-end">
                        <x-primary-button>
                            {{ __('交換を募集する') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
