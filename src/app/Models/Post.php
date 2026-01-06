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

    /**
     * 一括代入可能カラム
     */
    protected $fillable = [
        'user_id',
        'post_type',
        'title',    // ★ これが無いと title は保存されない
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
            Tag::class,
            'post_tag',
            'post_id',
            'tag_id'
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
     * いいねしたユーザー（多対多）
     */
    public function likers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'likes')
            ->withTimestamps();
    }

    /**
     * いいね数アクセサ
     * $post->likes_count
     */
    public function getLikesCountAttribute(): int
    {
        return $this->likers()->count();
    }

    /**
     * 指定ユーザーがいいねしているか
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
