<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
        'image_path',     // ★ 画像保存用
        'completed_at',
    ];

    /**
     * 日付として扱うカラム
     */
    protected $casts = [
        'completed_at' => 'datetime',
    ];

    /**
     * 出品者（交換を提案したユーザー）
     */
    public function proposer()
    {
        return $this->belongsTo(User::class, 'proposer_user_id');
    }

    /**
     * 交換相手（受信者）
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_user_id');
    }

    /**
     * 関連する投稿
     */
    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * この交換に紐づくチャットルーム
     */
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    /**
     * 画像URLアクセサ
     * $exchange->image_url で取得可能
     */
    public function getImageUrlAttribute(): ?string
    {
        if (!$this->image_path) {
            return null;
        }

        return Storage::url($this->image_path);
    }
}
