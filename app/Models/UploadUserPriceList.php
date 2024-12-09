<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadUserPriceList extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно не соответствует стандартному формату
    protected $table = 'upload_users_price_lists';

    // Укажите первичный ключ, если он не 'id'
    protected $primaryKey = 'id';

    // Если ваш первичный ключ не является автоинкрементным, установите это значение в false
    public $incrementing = true;

    // Укажите, что это поле является целым числом
    protected $keyType = 'int';

    // Укажите, какие поля можно массово заполнять
    protected $fillable = [
        'file_name',
        'file_content',
        'user_id',
        'file_path',
    ];

    // Если вы хотите отключить временные метки created_at и updated_at
    public $timestamps = false;
}