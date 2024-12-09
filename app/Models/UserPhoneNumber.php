<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhoneNumber extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно не соответствует стандартному формату
    protected $table = 'users_phone_number';

    // Укажите первичный ключ, если он не является 'id'
    protected $primaryKey = 'id';

    // Укажите, если первичный ключ не является автоинкрементным
    public $incrementing = true;

    // Укажите, если первичный ключ не является целым числом
    protected $keyType = 'int';

    // Укажите, какие поля могут быть массово назначены
    protected $fillable = [
        'user_id',
        'number_1',
    ];

    // Если вы хотите отключить автоматическое управление временными метками
    public $timestamps = false;

    // Опционально: определите отношения с другими моделями
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}