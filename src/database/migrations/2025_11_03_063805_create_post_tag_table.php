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
        Schema::create('post_tag', function (Blueprint $table) {
            // 投稿IDの外部キー（postsテーブル）
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();

            // タグIDの外部キー（tagsテーブル）
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();

            // post_id と tag_id の組み合わせを複合主キーに設定
            $table->primary(['post_id', 'tag_id']);

            // 作成日時・更新日時（すでに存在する場合はスキップ）
            if (!Schema::hasColumn('post_tag', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('post_tag', function (Blueprint $table) {
            if (Schema::hasColumn('post_tag', 'created_at')) {
                $table->dropTimestamps();
            }
        });

        Schema::dropIfExists('post_tag');
    }
};
