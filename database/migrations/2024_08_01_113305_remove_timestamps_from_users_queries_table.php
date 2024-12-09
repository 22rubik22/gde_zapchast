<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveTimestampsFromUsersQueriesTable extends Migration
{
    public function up()
    {
        Schema::table('users_queries', function (Blueprint $table) {
            // Удаляем поля created_at и updated_at
            $table->dropColumn(['created_at', 'updated_at']);
        });
    }

    public function down()
    {
        Schema::table('users_queries', function (Blueprint $table) {
            // Восстанавливаем поля created_at и updated_at
            $table->timestamps(); // Это создаст оба поля с типом timestamp
        });
    }
}

