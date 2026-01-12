<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MessageController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ========================================
// 公開ページ（ログイン不要）
// ========================================

// トップページ（投稿一覧）
Route::get('/', [PostController::class, 'index'])
    ->name('home');

// 投稿一覧
Route::get('/posts', [PostController::class, 'index'])
    ->name('posts.index');

// 投稿詳細
Route::get('/posts/{post}', [PostController::class, 'show'])
    ->whereNumber('post')
    ->name('posts.show');

// ユーザーページ（公開）
Route::get('/users/{user}', [UserController::class, 'show'])
    ->whereNumber('user')
    ->name('users.show');

// ----------------------------------------
// 物々交換（公開）
// ----------------------------------------

// 物々交換一覧
Route::get('/exchanges', [ExchangeController::class, 'index'])
    ->name('exchanges.index');

// 物々交換詳細（※チャットは含まない）
Route::get('/exchanges/{exchange}', [ExchangeController::class, 'show'])
    ->whereNumber('exchange')
    ->name('exchanges.show');


// ========================================
// 認証必須
// ========================================
Route::middleware('auth')->group(function () {

    // ------------------------------------
    // 投稿（CRUD）
    // ------------------------------------
    Route::get('/posts/create', [PostController::class, 'create'])
        ->name('posts.create');

    Route::post('/posts', [PostController::class, 'store'])
        ->name('posts.store');

    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])
        ->whereNumber('post')
        ->name('posts.edit');

    Route::put('/posts/{post}', [PostController::class, 'update'])
        ->whereNumber('post')
        ->name('posts.update');

    Route::delete('/posts/{post}', [PostController::class, 'destroy'])
        ->whereNumber('post')
        ->name('posts.destroy');

    // いいね
    Route::post('/posts/{post}/like', [PostController::class, 'like'])
        ->whereNumber('post')
        ->name('posts.like');

    Route::delete('/posts/{post}/unlike', [PostController::class, 'unlike'])
        ->whereNumber('post')
        ->name('posts.unlike');

    // コメント
    Route::post('/posts/{post}/comments', [CommentController::class, 'store'])
        ->whereNumber('post')
        ->name('comments.store');

    Route::delete('/comments/{comment}', [CommentController::class, 'destroy'])
        ->whereNumber('comment')
        ->name('comments.destroy');

    // ------------------------------------
    // 物々交換（ログイン必須）
    // ------------------------------------
    Route::get('/exchanges/create', [ExchangeController::class, 'create'])
        ->name('exchanges.create');

    Route::post('/exchanges', [ExchangeController::class, 'store'])
        ->name('exchanges.store');

    Route::get('/exchanges/{exchange}/edit', [ExchangeController::class, 'edit'])
        ->whereNumber('exchange')
        ->name('exchanges.edit');

    Route::put('/exchanges/{exchange}', [ExchangeController::class, 'update'])
        ->whereNumber('exchange')
        ->name('exchanges.update');

    Route::delete('/exchanges/{exchange}', [ExchangeController::class, 'destroy'])
        ->whereNumber('exchange')
        ->name('exchanges.destroy');

    // 承諾・拒否
    Route::post(
        '/exchanges/{exchange}/status/{status}',
        [ExchangeController::class, 'updateStatus']
    )
        ->whereNumber('exchange')
        ->name('exchanges.updateStatus');

    // ------------------------------------
    // チャット（★完成形）
    // ------------------------------------

    /**
     * チャット表示（exchange → room）
     * URL: /exchanges/{exchange}/room
     */
    Route::get(
        '/exchanges/{exchange}/room',
        [RoomController::class, 'show']
    )
        ->whereNumber('exchange')
        ->name('rooms.show');

    /**
     * メッセージ送信
     */
    Route::post(
        '/rooms/{room}/messages',
        [RoomController::class, 'send']
    )
        ->whereNumber('room')
        ->name('rooms.send');

    /**
     * メッセージ削除
     */
    Route::delete(
        '/messages/{message}',
        [MessageController::class, 'destroy']
    )
        ->whereNumber('message')
        ->name('messages.destroy');

    // ------------------------------------
    // ダッシュボード
    // ------------------------------------
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // ------------------------------------
    // プロフィール
    // ------------------------------------
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::patch('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::delete('/profile', [ProfileController::class, 'destroy'])
        ->name('profile.destroy');
});

// ========================================
// 認証関連
// ========================================
require __DIR__ . '/auth.php';
