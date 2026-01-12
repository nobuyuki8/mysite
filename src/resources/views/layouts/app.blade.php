{{-- resources/views/layouts/app.blade.php --}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', '東京家庭菜園') }}</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-100">
<div class="min-h-screen flex flex-col">

    {{-- ナビ --}}
    @include('layouts.navigation')

    {{-- ヘッダー --}}
    @isset($header)
        <div class="bg-gray-100 border-b">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                {{ $header }}
            </div>
        </div>
    @endisset

    {{-- ページ内容 --}}
    <main class="py-6">
        @isset($slot)
            {{ $slot }}
        @else
            {{-- もし $slot がない場合は section('content') を表示 --}}
            @yield('content')
        @endisset
    </main>

</div>

{{-- ★ スクリプト用 --}}
@stack('scripts')

</body>
</html>
