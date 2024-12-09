<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersQueriesTable extends Migration
{
    public function up()
    {
        Schema::table('users_queries', function (Blueprint $table) {
            // Удаляем уникальные индексы, если они есть
            // Например, если у вас есть уникальный индекс на id_queri:
            // $table->dropUnique(['id_queri']); // Убедитесь, что имя индекса правильное

            // Если вы хотите убедиться, что столбцы могут содержать дубликаты, вам не нужно ничего добавлять,
            // просто удалите уникальные индексы, если они существуют.
        });
    }

    public function down()
    {
        Schema::table('users_queries', function (Blueprint $table) {
            // Если вы хотите вернуть изменения, добавьте уникальные индексы обратно
            // Например:
            // $table->unique('id_queri'); // Добавьте нужные уникальные индексы обратно
        });
    }
}