<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaseAvtoTable extends Migration
{
    /**
     * Запускает миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('base_avto', function (Blueprint $table) {
            $table->string('id_modification')->primary(); // Устанавливаем id_modification как первичный ключ
            $table->string('brand'); // Бренд автомобиля
            $table->string('brand_(rus)'); // Бренд автомобиля на русском
            $table->string('model'); // Модель автомобиля
            $table->string('model_(rus)'); // Модель автомобиля на русском
            $table->string('generation'); // Поколение автомобиля
            $table->integer('year_from'); // Год выпуска (начало)
            $table->integer('year_before'); // Год выпуска (конец)
            $table->string('modification'); // Модификация автомобиля

            // Если нужно добавить временные метки, раскомментируйте следующую строку
            // $table->timestamps();
        });
    }

    /**
     * Откатывает миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('base_avto');
    }
}