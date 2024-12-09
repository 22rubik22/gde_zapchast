<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryUserSearchQuery extends Model
{
    use HasFactory;

    // Указываем имя таблицы, если оно не соответствует стандартному формату
    protected $table = 'history_user_search_queries';

    // Указываем первичный ключ, если он не 'id'
    protected $primaryKey = 'id';

    // Указываем, что первичный ключ является автоинкрементным
    public $incrementing = true;

    // Указываем, что модель использует временные метки
    public $timestamps = false; // Установите true, если хотите использовать created_at и updated_at

    // Указываем атрибуты, которые могут быть массово присвоены
    protected $fillable = [
        'user_id',
        'search_query',
        'brand',
        'model',
        'year',
        'city',
        'timestamp', // Если вы хотите использовать этот атрибут
    ];

    // Если вы хотите использовать кастомные временные метки, добавьте следующие строки
    // const CREATED_AT = 'created_at';
    // const UPDATED_AT = 'updated_at';

    // Определите любые дополнительные методы или отношения здесь
}