<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BaseAvto extends Model
{
    use HasFactory;

    protected $table = 'base_avto';

    // Указываем, что id_modification является первичным ключом
    protected $primaryKey = 'id_modification';
    public $incrementing = false; // Указываем, что ключ не автоинкрементный
    protected $keyType = 'string'; // Указываем тип ключа

    protected $fillable = [
        'id_modification',
        'brand',
        'brand_(rus)',
        'model',
        'model_(rus)',
        'generation',
        'year_from',
        'year_before',
        'modification'
    ];

    public $timestamps = false; // Установите true, если вы используете временные метки
}