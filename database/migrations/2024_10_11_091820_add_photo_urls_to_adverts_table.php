<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPhotoUrlsToAdvertsTable extends Migration
{
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->string('main_photo_url')->nullable();
            $table->string('additional_photo_url_1')->nullable();
            $table->string('additional_photo_url_2')->nullable();
            $table->string('additional_photo_url_3')->nullable();
        });
    }

    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            $table->dropColumn('main_photo_url');
            $table->dropColumn('additional_photo_url_1');
            $table->dropColumn('additional_photo_url_2');
            $table->dropColumn('additional_photo_url_3');
        });
    }
}