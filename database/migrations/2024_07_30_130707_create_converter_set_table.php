<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateConverterSetTable extends Migration
{
    /**
     * Запустите миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('converter_set', function (Blueprint $table) {
            $table->id(); // Создает автоинкрементный первичный ключ 'id'
            $table->unsignedBigInteger('user_id'); // Поле для идентификатора пользователя
            
            // Поля для автомобилей
            $table->integer('acura')->nullable();
            $table->string('alfa_romeo')->nullable();
            $table->integer('asia')->nullable();
            $table->string('aston_martin')->nullable();
            $table->integer('audi')->nullable();
            $table->integer('bentley')->nullable();
            $table->integer('bmw')->nullable();
            $table->integer('byd')->nullable();
            $table->integer('cadillac')->nullable();
            $table->integer('changan')->nullable();
            $table->integer('chevrolet')->nullable();
            $table->string('citroen')->nullable();
            $table->integer('daewoo')->nullable();
            $table->integer('daihatsu')->nullable();
            $table->integer('datsun')->nullable();
            $table->string('fiat')->nullable();
            $table->integer('ford')->nullable();
            $table->integer('gaz')->nullable();
            $table->integer('geely')->nullable();
            $table->integer('haval')->nullable();
            $table->integer('honda')->nullable();
            $table->integer('hyundai')->nullable();
            $table->string('infiniti')->nullable();
            $table->integer('isuzu')->nullable();
            $table->string('jaguar')->nullable();
            $table->integer('jeep')->nullable();
            $table->integer('kia')->nullable();
            $table->integer('lada')->nullable();
            $table->string('land_rover')->nullable();
            $table->integer('mazda')->nullable();
            $table->string('mercedes_benz')->nullable();
            $table->integer('mitsubishi')->nullable();
            $table->integer('nissan')->nullable();
            $table->integer('opel')->nullable();
            $table->string('peugeot')->nullable();
            $table->string('peugeot_lnonum')->nullable();
            $table->string('porsche')->nullable();
            $table->string('renault')->nullable();
            $table->integer('skoda')->nullable();
            $table->integer('ssangyong')->nullable();

            // Добавьте индексы, если это необходимо
            // Например, индекс для user_id
            $table->index('user_id');

            // Создание временных меток, если необходимо
            // $table->timestamps(); // Если хотите использовать created_at и updated_at
        });
    }

    /**
     * Отмените миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('converter_set');
    }
}