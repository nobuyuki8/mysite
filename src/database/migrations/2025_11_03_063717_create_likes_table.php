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
        Schema::create('likes', function (Blueprint $table) {
            // 主キー (Laravelの慣例としてIDは含めず、複合主キーを使用)
            // $table->id(); // 通常のIDは不要

            // 外部キー：いいねをしたユーザー
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // 外部キー：いいねの対象となった投稿
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();

            // user_id と post_id の組み合わせを複合主キーとして設定
            // これにより、同じユーザーが同じ投稿に複数回「いいね」するのを防ぐ
            $table->primary(['user_id', 'post_id']);

            // タイムスタンプ（「いついいねされたか」を記録するため）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('likes');
    }
};
