<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;

    /**
     * 一括代入を許可するカラム
     */
    protected $fillable = [
        'room_id',
        'sender_user_id',
        'user_id',
        'message',
    ];

    /**
     * メッセージ送信者（ユーザー）
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * このメッセージが属するチャットルーム
     */
    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
