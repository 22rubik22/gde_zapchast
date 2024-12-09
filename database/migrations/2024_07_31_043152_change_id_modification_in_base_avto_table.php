<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeIdModificationInBaseAvtoTable extends Migration
{
    public function up()
    {
        Schema::table('base_avto', function (Blueprint $table) {
            // Удаляем первичный ключ
            $table->dropPrimary('id_modification');
            
            // Добавляем индекс на id_modification
            $table->index('id_modification');
        });
    }

    public function down()
    {
        Schema::table('base_avto', function (Blueprint $table) {
            // Удаляем индекс, если нужно откатить миграцию
            $table->dropIndex(['id_modification']);
            
            // Восстанавливаем первичный ключ
            $table->primary('id_modification');
        });
    }
}