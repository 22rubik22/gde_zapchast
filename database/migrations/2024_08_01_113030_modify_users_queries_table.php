<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUsersQueriesTable extends Migration
{
    public function up()
    {
        Schema::table('users_queries', function (Blueprint $table) {
            // Удаляем столбец id
            $table->dropColumn('id');

            // Если хотите, чтобы id_queri был уникальным, добавьте уникальный индекс
            $table->unique('id_queri');
        });
    }

    public function down()
    {
        Schema::table('users_queries', function (Blueprint $table) {
            // Восстанавливаем столбец id
            $table->bigIncrements('id')->unsigned()->first();
            
            // Удаляем уникальный индекс, если он был установлен
            $table->dropUnique(['id_queri']);
        });
    }
}
