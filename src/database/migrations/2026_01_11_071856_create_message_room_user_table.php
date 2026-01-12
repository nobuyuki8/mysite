<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('message_room_user', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')
                  ->constrained()
                  ->cascadeOnDelete(); // ルーム削除時に自動削除
            $table->foreignId('user_id')
                  ->constrained()
                  ->cascadeOnDelete(); // ユーザー削除時に自動削除
            $table->timestamps();

            // 同じユーザーが同じルームに重複しないようにユニーク制約
            $table->unique(['room_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('message_room_user');
    }
};
