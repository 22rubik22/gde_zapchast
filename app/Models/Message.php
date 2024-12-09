<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', // Добавляем user_id
        'chat_id', // Если есть связь с чатом, добавьте chat_id
        'message',  // Поле для текста сообщения
        'is_read',  // статус сообщения
    ];


    public function user()
{
    return $this->belongsTo(User::class);
}
}
