<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('rooms', function (Blueprint $table) {

            // 外部キー制約を先に削除
            if (Schema::hasColumn('rooms', 'user1_id')) {
                $table->dropForeign(['user1_id']);
            }

            if (Schema::hasColumn('rooms', 'user2_id')) {
                $table->dropForeign(['user2_id']);
            }

            // カラム削除
            if (Schema::hasColumn('rooms', 'user1_id')) {
                $table->dropColumn('user1_id');
            }

            if (Schema::hasColumn('rooms', 'user2_id')) {
                $table->dropColumn('user2_id');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->foreignId('user1_id')->constrained('users');
            $table->foreignId('user2_id')->constrained('users');
        });
    }
};
