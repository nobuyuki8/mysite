<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();

            // 出品者
            $table->foreignId('proposer_user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // 取引相手（まだ決まっていない場合は null）
            $table->foreignId('receiver_user_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            // 関連投稿（不要なら null で OK）
            $table->foreignId('post_id')
                ->nullable()
                ->constrained()
                ->cascadeOnDelete();

            // 🌟 投稿内容
            $table->string('title', 100);
            $table->text('description');                // ← string ではなく text()
            $table->string('offered_crop_name', 50);
            $table->string('desired_crop_name', 50);
            $table->string('area', 50)->nullable();

            // 画像アップロード用
            $table->string('image_path')->nullable();

            // ステータス
            $table->enum('status', [
                'pending',
                'accepted',
                'rejected',
                'completed',
                'canceled'
            ])->default('pending');

            // 任意メッセージ
            $table->text('message')->nullable();

            // 完了日時
            $table->timestamp('completed_at')->nullable();

            // timestamps
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exchanges');
    }
};
