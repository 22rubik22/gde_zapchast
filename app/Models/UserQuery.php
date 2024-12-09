<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserQuery extends Model
{
    use HasFactory;

    protected $table = 'users_queries';

    protected $fillable = [
        'id_queri',
        'id_part',
        'id_car',
    ];

    public $incrementing = false; // Если id_queri не автоинкрементный, установите это значение в false
    protected $primaryKey = null; // Убираем первичный ключ
    protected $keyType = 'string'; // Или 'int', в зависимости от типа данных id_queri

    // Указываем, что модель не использует временные метки
    public $timestamps = false; // Отключаем автоматическое управление временными метками
}