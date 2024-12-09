<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConverterSet extends Model
{
    use HasFactory;

    // Укажите имя таблицы, если оно не соответствует стандартному формату
    protected $table = 'converter_set';

    // Укажите первичный ключ, если он не 'id'
    protected $primaryKey = 'id';

    // Укажите, если первичный ключ не является автоинкрементным
    public $incrementing = true;

    // Укажите тип данных первичного ключа
    protected $keyType = 'int';

    // Укажите, если временные метки не используются
    public $timestamps = false;

    // Укажите заполняемые поля
    protected $fillable = [
        'user_id',
        'acura',
        'alfa_romeo',
        'asia',
        'aston_martin',
        'audi',
        'bentley',
        'bmw',
        'byd',
        'cadillac',
        'changan',
        'chevrolet',
        'citroen',
        'daewoo',
        'daihatsu',
        'datsun',
        'fiat',
        'ford',
        'gaz',
        'geely',
        'haval',
        'honda',
        'hyundai',
        'infiniti',
        'isuzu',
        'jaguar',
        'jeep',
        'kia',
        'lada',
        'land_rover',
        'mazda',
        'mercedes_benz',
        'mitsubishi',
        'nissan',
        'opel',
        'peugeot',
        'peugeot_lnonum',
        'porsche',
        'renault',
        'skoda',
        'ssangyong',
        'subaru', 
        'suzuki', 
        'toyota', 
        'uaz', 
        'volkswagen', 
        'volvo', 
        'zaz', 
        'product_name', 
        'price', 
        'car_brand', 
        'car_model', 
        'year', 
        'oem_number', 
        'picture', 
        'body', 
        'engine', 
        'quantity', 
        'text_declaration', 
        'left_right', 
        'up_down', 
        'front_back', 
        'fileformat_col', 
        'encoding', 
        'file_price', 
        'my_file', 
        'header_str_col', 
        'separator_col', 
        'del_duplicate', 
        'art_number', 
        'availability', 
        'color', 
        'delivery_time', 
        'new_used', 
        'many_pages_col'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}
}
