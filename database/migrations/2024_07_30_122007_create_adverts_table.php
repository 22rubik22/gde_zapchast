<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdvertsTable extends Migration
{
    /**
     * Запустите миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('adverts', function (Blueprint $table) {
            $table->id(); // Автоинкрементный первичный ключ
            $table->unsignedBigInteger('user_id'); // Внешний ключ для связи с пользователем
            $table->string('art_number'); // Арт. номер
            $table->string('product_name'); // Название товара
            $table->enum('new_used', ['new', 'used']); // Новый или б/у
            $table->string('brand'); // Бренд
            $table->string('model'); // Модель
            $table->string('body'); // Кузов
            $table->string('number'); // Номер
            $table->string('engine'); // Двигатель
            $table->year('year'); // Год
            $table->string('L_R'); // Левый/правый
            $table->string('F_R'); // Передний/задний
            $table->string('U_D'); // Верхний/нижний
            $table->string('color'); // Цвет
            $table->string('applicability'); // Применимость
            $table->string('quantity'); // Количество
            $table->integer('price'); // Цена
            $table->boolean('availability'); // Наличие (true/false)
            $table->integer('delivery_time'); // Время доставки
            $table->string('photo')->nullable(); // Фото (может быть NULL)
            $table->dateTime('data')->nullable(); // Дата (может быть NULL)
            $table->enum('status_ad', ['active', 'inactive']); // Статус объявления
            $table->integer('id_ad')->unique(); // Уникальный идентификатор объявления

            // Добавление внешнего ключа для user_id, если у вас есть таблица users
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->timestamps(); // Если хотите добавить created_at и updated_at (можно удалить, если не нужно)
        });
    }

    /**
     * Откатите миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('adverts');
    }
}
