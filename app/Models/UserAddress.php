<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно не соответствует стандартному
    protected $table = 'user_addresses';

    // Укажите первичный ключ, если он не соответствует стандартному
    protected $primaryKey = 'id';

    // Установите автоинкрементный ключ (по умолчанию true)
    public $incrementing = true;

    // Укажите тип первичного ключа
    protected $keyType = 'int';

    // Разрешите массовое присвоение для указанных полей
    protected $fillable = [
        'user_id',
        'address_line',
        'city',
        'region',
        'street',
        'house',
        'postal_code',
    ];

    // Установите даты, которые будут автоматически управляться Laravel
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Если вы хотите установить связь с моделью User (если такая модель существует)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}