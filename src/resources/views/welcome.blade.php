@extends('layouts.app')

@section('content')
    <div class="text-center py-24">

        <h1 class="text-3xl sm:text-4xl font-bold mb-4">
            🌱 家庭菜園コミュニティへようこそ
        </h1>

        <p class="text-gray-600 max-w-2xl mx-auto leading-relaxed">
            東京で家庭菜園を楽しむ人たちが、<br class="hidden sm:block">
            投稿・交流・物々交換できるサービスです。
        </p>

        <div class="mt-10 flex justify-center gap-4">
            @auth
                <a href="{{ route('posts.index') }}"
                   class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700">
                    投稿一覧を見る
                </a>
            @else
                <a href="{{ route('register') }}"
                   class="px-6 py-3 bg-green-600 text-white rounded-md hover:bg-green-700">
                    会員登録
                </a>
                <a href="{{ route('login') }}"
                   class="px-6 py-3 border border-green-600 text-green-600 rounded-md hover:bg-green-50">
                    ログイン
                </a>
            @endauth
        </div>
    </div>
@endsection
