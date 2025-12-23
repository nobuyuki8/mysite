<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'content',
        'image',
        'area',
    ];

    /**
     * 投稿者（ユーザー）
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * タグ（多対多）
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(
            Tag::class,     // 関連先モデル
            'post_tag',     // 中間テーブル
            'post_id',      // このモデル側の外部キー
            'tag_id'        // Tagモデル側
        )->withTimestamps();
    }

    /**
     * コメント（1対多）
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * いいねしたユーザー一覧（多対多）
     */
    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')->withTimestamps();
    }

    /**
     * いいね数を取得（アクセサ）
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likers()->count();
    }

    /**
     * ログインユーザーがいいねしているか？
     */
    public function isLikedByUser(?int $userId): bool
    {
        if (!$userId) {
            return false;
        }

        return $this->likers()
            ->where('user_id', $userId)
            ->exists();
    }
}

