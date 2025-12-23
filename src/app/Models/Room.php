<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = ['exchange_id'];

    // ルームに紐づく交換
    public function exchange()
    {
        return $this->belongsTo(Exchange::class);
    }

    // メッセージ一覧
    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
