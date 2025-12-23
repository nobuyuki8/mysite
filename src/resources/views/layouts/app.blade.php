<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- アプリ名 --}}
    <title>{{ config('app.name', '東京家庭菜園') }}</title>

    {{-- CSRF トークン --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- CSS / JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">

    <div class="min-h-screen">

        {{-- ★ アプリ共通ヘッダー --}}
        <header class="bg-white text-black py-4 px-6 shadow">
            <div class="max-w-7xl mx-auto flex items-center gap-3 text-2xl font-bold">
                <span class="text-green-600 text-3xl">🍃</span>
                <span>東京家庭菜園</span>
            </div>
        </header>

        {{-- ★ 各ページのタイトル --}}
        @isset($header)
            <div class="bg-gray-100 border-b">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </div>
        @endisset

        {{-- ページ内容 --}}
        <main class="py-6">
            {{ $slot }}
        </main>

    </div>

</body>
</html>
