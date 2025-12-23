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
        // ユーザーと作物を紐づける中間テーブルを作成
        Schema::create('user_crops', function (Blueprint $table) {
            // 外部キー：ユーザーID (usersテーブルを参照)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            
            // 外部キー：作物ID (cropsテーブルを参照)
            $table->foreignId('crop_id')->constrained()->cascadeOnDelete();

            // 複合主キーの定義 (同じ作物を2回登録させない)
            $table->primary(['user_id', 'crop_id']);

            // ユーザーがいつからその作物を育て始めたか（任意情報）
            $table->date('start_date')->nullable();

            // タイムスタンプ（作成日時、更新日時）
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_crops');
    }
};