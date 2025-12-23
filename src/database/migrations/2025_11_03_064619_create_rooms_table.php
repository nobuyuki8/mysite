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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();

            // 物々交換投稿に紐づくルーム
            $table->foreignId('exchange_id')
                  ->constrained('exchanges')
                  ->cascadeOnDelete();

            // 必要に応じて、ルームの名前や説明などのカラムも追加可能
            // $table->string('name')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
