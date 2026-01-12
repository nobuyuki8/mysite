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

    // ====================================
    // 一括代入可能なカラム
    // ====================================
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

    // ====================================
    // 非表示カラム
    // ====================================
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // ====================================
    // 属性キャスト
    // ====================================
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // ====================================
    // 定数管理
    // ====================================

    // ユーザーロール
    const ROLE_USER  = 'user';
    const ROLE_ADMIN = 'admin';

    public static function roles(): array
    {
        return [
            self::ROLE_USER,
            self::ROLE_ADMIN,
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
     * 投稿（物々交換の提案者）
     */
    public function exchanges(): HasMany
    {
        return $this->hasMany(Exchange::class, 'proposer_user_id');
    }

    /**
     * 投稿（物々交換の受取側）
     */
    public function receivedExchanges(): HasMany
    {
        return $this->hasMany(Exchange::class, 'receiver_user_id');
    }

    /**
     * 栽培中の作物（多対多）
     */
    public function growingCrops(): BelongsToMany
    {
        return $this->belongsToMany(Crop::class, 'user_crops');
    }

    /**
     * チャットルーム（多対多）
     */
    public function messageRooms(): BelongsToMany
    {
        return $this->belongsToMany(MessageRoom::class, 'message_room_user');
    }

    /**
     * メッセージ（1対多）
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class);
    }

    /**
     * コメント（1対多）
     */
    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    // ====================================
    // アクセサ
    // ====================================

    /**
     * プロフィール画像URL
     * $user->profile_image_url で取得可能
     */
    public function getProfileImageUrlAttribute(): ?string
    {
        if (!$this->profile_image) {
            return null;
        }

        return asset('storage/' . $this->profile_image);
    }

    // ====================================
    // 投稿タイプの定数（Post モデル参照用）
    // ====================================
    // User モデルに直接書くこともできますが、
    // Post モデルにまとめる方が管理しやすいです。
    // const POST_TYPE_NORMAL   = 'normal';
    // const POST_TYPE_EXCHANGE = 'exchange';
}
