<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersQueriesTable extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_queries', function (Blueprint $table) {
            $table->id(); // Автоинкрементный первичный ключ
            $table->unsignedBigInteger('id_queri'); // Поле для id_queri
            $table->unsignedBigInteger('id_part');  // Поле для id_part
            $table->unsignedBigInteger('id_car');   // Поле для id_car
            $table->timestamps(); // Поля created_at и updated_at
        });
    }

    /**
     * Обратное действие миграции.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_queries');
    }
}