<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            // 主キー
            $table->id();

            // 外部キー：コメント投稿者 (usersテーブルを参照)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // 外部キー：コメント対象の投稿 (postsテーブルを参照)
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();

            // コメント内容
            $table->text('content');

            // タイムスタンプ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('comments');
    }
};