<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsToConverterSetTable extends Migration
{
    public function up()
    {
        Schema::table('converter_set', function (Blueprint $table) {
            // Добавление новых полей
            $table->boolean('subaru')->default(false);
            $table->boolean('suzuki')->default(false);
            $table->boolean('toyota')->default(false);
            $table->boolean('uaz')->default(false);
            $table->boolean('volkswagen')->default(false);
            $table->boolean('volvo')->default(false);
            $table->boolean('zaz')->default(false);

            // Поля для текстовых значений
            $table->string('product_name')->nullable();
            $table->string('price')->nullable();
            $table->string('car_brand')->nullable();
            $table->string('car_model')->nullable();
            $table->string('year')->nullable();
            $table->string('oem_number')->nullable();
            $table->string('picture')->nullable();
            $table->string('body')->nullable();
            $table->string('engine')->nullable();
            $table->string('quantity')->nullable();
            $table->string('text_declaration')->nullable();
            $table->string('left_right')->nullable();
            $table->string('up_down')->nullable();
            $table->string('front_back')->nullable();
            $table->string('fileformat_col')->nullable();
            $table->string('encoding')->nullable();
            $table->string('file_price')->nullable();
            $table->string('my_file')->nullable();
            $table->string('header_str_col')->nullable();
            $table->string('separator_col')->nullable();
            $table->string('del_duplicate')->nullable();
            $table->string('art_number')->nullable();
            $table->string('availability')->nullable();
            $table->string('color')->nullable();
            $table->string('delivery_time')->nullable();
            $table->string('new_used')->nullable();
            $table->string('many_pages_col')->nullable();

        });
    }

    public function down()
    {
        Schema::table('converter_set', function (Blueprint $table) {
            // Удаление полей в случае отката миграции
            $table->dropColumn('subaru');
            $table->dropColumn('suzuki');
            $table->dropColumn('toyota');
            $table->dropColumn('uaz');
            $table->dropColumn('volkswagen');
            $table->dropColumn('volvo');
            $table->dropColumn('zaz');
            $table->dropColumn('product_name');
            $table->dropColumn('price');
            $table->dropColumn('car_brand');
            $table->dropColumn('car_model');
            $table->dropColumn('year');
            $table->dropColumn('oem_number');
            $table->dropColumn('picture');
            $table->dropColumn('body');
            $table->dropColumn('engine');
            $table->dropColumn('quantity');
            $table->dropColumn('text_declaration');
            $table->dropColumn('left_right');
            $table->dropColumn('up_down');
            $table->dropColumn('front_back');
            $table->dropColumn('fileformat_col');
            $table->dropColumn('file_price');
            $table->dropColumn('my_file');
            $table->dropColumn('header_str_col');
            $table->dropColumn('separator_col');
            $table->dropColumn('del_duplicate');
            $table->dropColumn('art_number');
            $table->dropColumn('availability');
            $table->dropColumn('color');
            $table->dropColumn('delivery_time');
            $table->dropColumn('new_used');
            $table->dropColumn('many_pages_col');
        });
    }
}