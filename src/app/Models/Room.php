<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;

    /**
     * 一括代入可能なカラム（★ここが重要）
     */
    protected $fillable = [
        'exchange_id',
        'user1_id',
        'user2_id',
    ];

    // ====================================
    // リレーション
    // ====================================

    /**
     * 物々交換（1交換 : 1ルーム）
     */
    public function exchange(): BelongsTo
    {
        return $this->belongsTo(Exchange::class);
    }

    /**
     * メッセージ
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * 参加ユーザー（多対多）
     * 中間テーブル: message_room_user
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'message_room_user')
            ->withTimestamps();
    }

    // ====================================
    // ヘルパー
    // ====================================

    /**
     * 指定ユーザーがこのルームに参加しているか
     */
    public function hasUser(int $userId): bool
    {
        return $this->users()
            ->where('users.id', $userId)
            ->exists();
    }
}
