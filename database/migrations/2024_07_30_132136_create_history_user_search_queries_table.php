<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoryUserSearchQueriesTable extends Migration
{
    /**
     * Запуск миграции.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('history_user_search_queries', function (Blueprint $table) {
            $table->id(); // Создает автоинкрементный первичный ключ 'id'
            $table->unsignedBigInteger('user_id'); // Поле для идентификатора пользователя
            $table->string('search_query'); // Поле для поискового запроса
            $table->string('brand')->nullable(); // Поле для марки (может быть пустым)
            $table->string('model')->nullable(); // Поле для модели (может быть пустым)
            $table->year('year')->nullable(); // Поле для года (может быть пустым)
            $table->string('city')->nullable(); // Поле для города (может быть пустым)
            $table->timestamp('timestamp')->nullable(); // Поле для временной метки (может быть пустым)

            // Индексы, если необходимо
            $table->index('user_id'); // Индекс по полю user_id

            // Добавление внешнего ключа, если необходимо
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Откат миграции.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('history_user_search_queries');
    }
}