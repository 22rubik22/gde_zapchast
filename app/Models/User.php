<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'city',
        'car_model',
        'vin',
        'confirmation_code',
        'sender_id',
        'receiver_id',
        'user_status',
        'is_staff',
        'is_superuser',
        'last_login',
        'last_name',
        'first_name',
        'is_active',
        'date_joined',
        'avatar_url',
        'logo_url',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token', // Используется для функции "запомнить меня"
        'confirmation_code', // Если вы не хотите показывать этот код
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'last_login' => 'datetime',
        'date_joined' => 'datetime',
        'is_staff' => 'boolean',
        'is_superuser' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * The name of the "created at" column.
     *
     * @var string
     */
    const CREATED_AT = 'date_joined';

    /**
     * The name of the "updated at" column.
     *
     * @var string|null
     */
    const UPDATED_AT = null; // Укажите название колонки для обновлений, если есть

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'users'; // Имя таблицы 

    /**
     * Получить чаты, отправленные пользователем.
     */
    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    /**
     * Получить чаты, полученные пользователем.
     */
    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }

    /**
     * Получить адрес пользователя.
     */
    public function userAddress()
    {
        return $this->hasOne(UserAddress::class);
    }

    public function userPhoneNumber()
{
    return $this->hasOne(UserPhoneNumber::class);
}

public function chatsAsUser1()
{
    return $this->hasMany(Chat::class, 'user1_id');
}

public function chatsAsUser2()
{
    return $this->hasMany(Chat::class, 'user2_id');
}

public function messages()
{
    return $this->hasMany(Message::class);
}

public function converterSets()
{
    return $this->hasMany(ConverterSet::class);
}

}