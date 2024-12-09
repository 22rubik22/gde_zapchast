<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdvertsDefaultValues extends Migration
{
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Установите значения по умолчанию для всех нужных полей
            $table->string('art_number')->nullable()->default(null)->change();
            $table->string('product_name')->nullable()->default(null)->change();
            $table->string('new_used')->nullable()->default(null)->change();
            $table->string('brand')->nullable()->default(null)->change();
            $table->string('model')->nullable()->default(null)->change();
            $table->string('body')->nullable()->default(null)->change();
            $table->string('number')->nullable()->default(null)->change();
            $table->string('engine')->nullable()->default(null)->change();
            $table->integer('year')->nullable()->default(null)->change();
            $table->string('L_R')->nullable()->default(null)->change();
            $table->string('F_R')->nullable()->default(null)->change();
            $table->string('U_D')->nullable()->default(null)->change();
            $table->string('color')->nullable()->default(null)->change();
            $table->string('applicability')->nullable()->default(null)->change();
            $table->integer('quantity')->nullable()->default(null)->change();
            $table->decimal('price', 10, 2)->nullable()->default(null)->change(); // Пример для decimal
            $table->string('availability')->nullable()->default(null)->change();
            $table->string('delivery_time')->nullable()->default(null)->change();
            $table->string('photo')->nullable()->default(null)->change();
            $table->dateTime('data')->nullable()->default(null)->change(); // datetime
            $table->string('status_ad')->nullable()->default(null)->change();
            $table->string('id_ad')->nullable()->default(null)->change();
        });
    }

    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Вернуть старые значения по умолчанию, если это необходимо
            // Пример:
            // $table->string('art_number')->default('old_default_value')->change();
        });
    }
}

