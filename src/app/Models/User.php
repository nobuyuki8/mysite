<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * 一括代入可能なカラム
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'area',
        'introduction',
        'favorite_crop',
        'profile_image',     // ★ プロフィール画像
        'gardening_experience',
    ];

    /**
     * 非表示カラム
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * 属性キャスト
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // ====================================
    // リレーション
    // ====================================

    /**
     * 投稿（posts）
     */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /**
     * 物々交換（提案者）
     */
    public function exchanges(): HasMany
    {
        return $this->hasMany(
            Exchange::class,
            'proposer_user_id'
        );
    }

    /**
     * 交換（受取側）
     */
    public function receivedExchanges(): HasMany
    {
        return $this->hasMany(
            Exchange::class,
            'receiver_user_id'
        );
    }

    /**
     * 栽培中の作物
     */
    public function growingCrops(): BelongsToMany
    {
        return $this->belongsToMany(
            Crop::class,
            'user_crops'
        );
    }

    /**
     * チャットルーム
     */
    public function messageRooms(): BelongsToMany
    {
        return $this->belongsToMany(
            MessageRoom::class,
            'message_room_user'
        );
    }

    /**
     * メッセージ
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    // ====================================
    // アクセサ
    // ====================================

    /**
     * プロフィール画像URL
     * $user->profile_image_url で取得
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        if (!$this->profile_image) {
            return null;
        }

        return asset('storage/' . $this->profile_image);
    }
}
