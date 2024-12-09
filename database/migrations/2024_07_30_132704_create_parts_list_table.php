<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePartsListTable extends Migration
{
    /**
     * Запустите миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('parts_list', function (Blueprint $table) {
            $table->id('part_id'); // Создание автоинкрементного первичного ключа
            $table->string('part_name'); // Поле для названия детали
            $table->timestamps(); // Если вы хотите использовать временные метки created_at и updated_at
        });
    }

    /**
     * Отмените миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parts_list');
    }
}