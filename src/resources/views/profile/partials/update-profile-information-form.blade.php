<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Profile Information') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __("Update your account's profile information and email address.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    {{-- ★ 変更点 1: enctype="multipart/form-data" を追加 ★ --}}
    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        {{-- 既存の Name/Email の入力欄 (省略せずに含める) --}}
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                {{-- メール認証の通知など (省略) --}}
            @endif
        </div>
        
        {{-- ★★★ ここからカスタムフィールドを追加 ★★★ --}}

        {{-- 1. プロフィール画像の入力 --}}
        <div class="flex items-center space-x-4">
            {{-- 現在の画像表示 --}}
            @if ($user->profile_image)
                {{-- Storage::url() で publicディスクに保存した画像パスをWebアクセス可能なURLに変換 --}}
                <img src="{{ Storage::url($user->profile_image) }}" alt="Profile Image" class="w-16 h-16 rounded-full object-cover">
            @else
                <div class="w-16 h-16 rounded-full bg-gray-200 flex items-center justify-center">No Image</div>
            @endif

            <div>
                <x-input-label for="profile_image" :value="__('Profile Image')" />
                {{-- input type="file" を追加 --}}
                <input id="profile_image" name="profile_image" type="file" class="mt-1 block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-violet-50 file:text-violet-700
                    hover:file:bg-violet-100" />
                <x-input-error class="mt-2" :messages="$errors->get('profile_image')" />
            </div>
        </div>


        {{-- 2. エリアの入力 --}}
        <div>
            <x-input-label for="area" :value="__('Area (e.g., Setagaya-ku)')" />
            <x-text-input id="area" name="area" type="text" class="mt-1 block w-full" :value="old('area', $user->area)" autocomplete="area" />
            <x-input-error class="mt-2" :messages="$errors->get('area')" />
        </div>

        {{-- 3. 家庭菜園歴の入力 --}}
        <div>
            <x-input-label for="gardening_experience" :value="__('Gardening Experience')" />
            <x-text-input id="gardening_experience" name="gardening_experience" type="text" class="mt-1 block w-full" :value="old('gardening_experience', $user->gardening_experience)" autocomplete="gardening_experience" />
            <x-input-error class="mt-2" :messages="$errors->get('gardening_experience')" />
        </div>

        {{-- 4. 得意な作物の入力 --}}
        <div>
            <x-input-label for="favorite_crop" :value="__('Favorite Crop')" />
            <x-text-input id="favorite_crop" name="favorite_crop" type="text" class="mt-1 block w-full" :value="old('favorite_crop', $user->favorite_crop)" autocomplete="favorite_crop" />
            <x-input-error class="mt-2" :messages="$errors->get('favorite_crop')" />
        </div>

        {{-- 5. 紹介文の入力 --}}
        <div>
            <x-input-label for="introduction" :value="__('Introduction')" />
            {{-- text-inputの代わりに textarea を使用 --}}
            <textarea id="introduction" name="introduction" rows="4" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" autocomplete="introduction">{{ old('introduction', $user->introduction) }}</textarea>
            <x-input-error class="mt-2" :messages="$errors->get('introduction')" />
        </div>
        
        {{-- ★★★ カスタムフィールドの追加ここまで ★★★ --}}


        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Save') }}</x-primary-button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Saved.') }}</p>
            @endif
        </div>
    </form>
</section>