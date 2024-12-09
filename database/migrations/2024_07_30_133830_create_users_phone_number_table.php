<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersPhoneNumberTable extends Migration
{
    /**
     * Запустите миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users_phone_number', function (Blueprint $table) {
            $table->id(); // Автоинкрементный первичный ключ
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Внешний ключ на таблицу users
            $table->string('number_1'); // Поле для хранения номера телефона
            $table->timestamps(); // Поля created_at и updated_at
        });
    }

    /**
     * Отмените миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_phone_number');
    }
}