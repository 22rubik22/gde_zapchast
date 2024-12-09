<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Advert extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно не соответствует стандартному имени
    protected $table = 'adverts';

    // Укажите первичный ключ, если он не 'id'
    protected $primaryKey = 'id';

    // Укажите, если первичный ключ не является автоинкрементом
    public $incrementing = true;

    // Укажите, если ваша таблица использует временные метки (created_at и updated_at)
    public $timestamps = false;

    // Укажите атрибуты, которые могут быть массово присвоены
    protected $fillable = [
        'user_id',
        'art_number',
        'product_name',
        'new_used',
        'brand',
        'model',
        'body',
        'number',
        'engine',
        'year',
        'L_R',
        'F_R',
        'U_D',
        'color',
        'applicability',
        'quantity',
        'price',
        'availability',
        'delivery_time',
        'photo',
        'data', // datetime
        'status_ad',
        'id_ad',
        'main_photo_url',
        'additional_photo_url_1',
        'additional_photo_url_2',
        'additional_photo_url_3',
    ];

    // Укажите атрибуты, которые должны быть приведены к типу
    protected $casts = [
        'data' => 'datetime', // Приведение к типу datetime
        'quantity' => 'string', // Приведение к типу string
        'year' => 'string', // Приведение к типу string
        'price' => 'integer', // Приведение к типу integer, если нужно
    ];


    // Установите значение по умолчанию для status_ad
    protected $attributes = [
        'status_ad' => 'activ', // Значение по умолчанию
    ];


    // Если у вас есть отношения с другими моделями, определите их здесь
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Другие методы и логика модели могут быть добавлены здесь
    
}