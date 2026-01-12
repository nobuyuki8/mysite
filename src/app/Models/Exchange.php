<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class Exchange extends Model
{
    use HasFactory;

    /**
     * 一括代入可能なカラム
     */
    protected $fillable = [
        'proposer_user_id',
        'receiver_user_id',
        'post_id',
        'title',
        'description',
        'offered_crop_name',
        'desired_crop_name',
        'area',
        'status',
        'image_path',
        'completed_at',
    ];

    /**
     * キャスト
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * 出品者（交換を提案したユーザー）
     */
    public function proposer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'proposer_user_id');
    }

    /**
     * 交換相手（承諾したユーザー）
     */
    public function receiver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    /**
     * 関連する投稿
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * チャットルーム（1交換 : 1ルーム）
     */
    public function room(): HasOne
    {
        return $this->hasOne(Room::class);
    }

    /**
     * 画像URLアクセサ
     * $exchange->image_url で取得可能
     */
    public function getImageUrlAttribute(): ?string
    {
        if (empty($this->image_path)) {
            return null;
        }

        return Storage::url($this->image_path);
    }
}
