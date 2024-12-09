<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Part extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно не соответствует стандартному формату
    protected $table = 'parts_list';

    // Укажите первичный ключ, если он не соответствует стандартному формату
    protected $primaryKey = 'part_id';

    // Укажите, если первичный ключ не является автоинкрементным
    public $incrementing = true; // true, если part_id является автоинкрементным

    // Укажите тип данных первичного ключа (по умолчанию - int)
    protected $keyType = 'int';

    // Укажите, какие атрибуты могут быть заполнены массово
    protected $fillable = [
        'part_name',
    ];

    // Укажите, если вы хотите отключить временные метки created_at и updated_at
    public $timestamps = false; // false, если вы не используете временные метки
}