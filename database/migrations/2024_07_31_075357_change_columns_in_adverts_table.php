<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnsInAdvertsTable extends Migration
{
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Изменяем тип данных
            $table->text('applicability')->change();
            $table->string('quantity')->change(); // varchar по умолчанию с длиной 255
            $table->string('year')->change(); // varchar по умолчанию с длиной 255
        });
    }

    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Возвращаем типы данных к исходным
            $table->string('applicability')->change(); // Верните к строке, если необходимо
            $table->integer('quantity')->change(); // Верните к integer, если необходимо
            $table->integer('year')->change(); // Верните к integer, если необходимо
        });
    }
}