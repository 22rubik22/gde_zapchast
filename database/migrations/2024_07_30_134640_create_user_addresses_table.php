<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserAddressesTable extends Migration
{
    /**
     * Запустите миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id(); // Создает автоинкрементный первичный ключ 'id'
            $table->unsignedBigInteger('user_id'); // Внешний ключ для пользователя
            $table->string('address_line'); // Строка для адреса
            $table->string('city'); // Город
            $table->string('region'); // Регион
            $table->string('street'); // Улица
            $table->string('house'); // Номер дома
            $table->string('postal_code'); // Почтовый индекс
            $table->timestamps(); // Создает поля created_at и updated_at

            // Устанавливаем внешний ключ (если у вас есть модель User)
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Откатите миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_addresses');
    }
}