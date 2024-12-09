<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RussiaCity extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно не соответствует стандартному имени
    protected $table = 'russia_citys';

    // Укажите первичный ключ, если он отличается от 'id'
    protected $primaryKey = 'city';

    // Укажите, если первичный ключ не автоинкрементный
    public $incrementing = false;

    // Укажите, если первичный ключ является строкой
    protected $keyType = 'string';

    // Укажите, какие поля могут быть массово назначены
    protected $fillable = [
        'city',
    ];

    // Если вы не хотите использовать временные метки created_at и updated_at
    public $timestamps = false;
}