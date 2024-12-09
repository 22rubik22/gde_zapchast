<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRussiaCitysTable extends Migration
{
    /**
     * Запустите миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('russia_citys', function (Blueprint $table) {
            $table->string('city')->primary(); // Поле city как первичный ключ
        });
    }

    /**
     * Отмените миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('russia_citys');
    }
}
