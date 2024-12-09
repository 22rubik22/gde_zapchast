<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    // Укажите атрибуты, которые могут быть массово присвоены
    protected $fillable = [
        'user1_id',
        'user2_id',
        'advert_id',
        // Добавьте другие поля, если необходимо
    ];

    public function user1()
{
    return $this->belongsTo(User::class, 'user1_id');
}

public function user2()
{
    return $this->belongsTo(User::class, 'user2_id');
}


public function advert()
{
    return $this->belongsTo(Advert::class);
}

public function messages()
{
    return $this->hasMany(Message::class);
}
public function chatsAsUser1()
{
    return $this->hasMany(Chat::class, 'user1_id');
}

public function chatsAsUser2()
{
    return $this->hasMany(Chat::class, 'user2_id');
}

public function getLastMessageAttribute()
{
    return $this->messages()->latest()->first();
}

public function last_message()
    {
        return $this->hasOne(Message::class)->latest();
    }

}
