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
        Schema::table('messages', function (Blueprint $table) {
            // user_id を追加（users テーブルの id と紐づく）
            $table->foreignId('user_id')
                ->constrained('users')
                ->after('room_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            // 外部キー削除
            $table->dropForeign(['user_id']);

            // カラム削除
            $table->dropColumn('user_id');
        });
    }
};
