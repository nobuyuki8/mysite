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
        Schema::table('users', function (Blueprint $table) {
            // エリア（例：世田谷区）
            $table->string('area', 50)->nullable()->after('email'); 
            
            // プロフィール画像パス
            $table->string('profile_image')->nullable()->after('area');
            
            // 紹介文 (長文の可能性があるのでtext型を使用)
            $table->text('introduction')->nullable()->after('profile_image');
            
            // 家庭菜園歴
            $table->string('gardening_experience', 100)->nullable()->after('introduction'); 
            
            // 得意な作物
            $table->string('favorite_crop', 100)->nullable()->after('gardening_experience');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // downメソッドでは、upで追加したカラムを削除（ロールバック用）
            $table->dropColumn([
                'area',
                'profile_image',
                'introduction',
                'gardening_experience',
                'favorite_crop',
            ]);
        });
    }
};