<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdvertsTableSetDefaults extends Migration
{
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Устанавливаем значение по умолчанию для полей
            $table->string('year')->nullable()->default(null)->change();
            $table->text('applicability')->nullable()->default(null)->change();
            $table->string('quantity')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Возвращаем поля к исходному состоянию
            $table->string('year')->nullable(false)->change(); // или просто уберите nullable()
            $table->text('applicability')->nullable(false)->change();
            $table->string('quantity')->nullable(false)->change();
        });
    }
}
