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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();

            // 投稿者のユーザーID
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // 投稿の種類（通常投稿 / 交換用投稿 など）
            $table->string('post_type')->default('normal');

            // 投稿内容
            $table->text('content')->nullable();

            // 投稿画像（任意）
            $table->string('image')->nullable();

            // エリア（任意）
            $table->string('area')->nullable();

            // タイムスタンプ
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
