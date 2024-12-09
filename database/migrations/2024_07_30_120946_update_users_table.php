<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Добавление новых колонок
           
            $table->string('city')->nullable()->after('username');
            $table->string('car_model')->nullable()->after('city');
            $table->string('vin')->nullable()->after('car_model');
            $table->string('confirmation_code')->nullable()->after('vin');
            $table->unsignedBigInteger('sender_id')->nullable()->after('confirmation_code');
            $table->unsignedBigInteger('receiver_id')->nullable()->after('sender_id');
            $table->boolean('user_status')->default(1)->after('receiver_id');
            $table->boolean('is_staff')->default(0)->after('user_status');
            $table->boolean('is_superuser')->default(0)->after('is_staff');
            $table->timestamp('last_login')->nullable()->after('is_superuser');
            $table->string('last_name')->nullable()->after('last_login');
            $table->string('first_name')->nullable()->after('last_name');
            $table->boolean('is_active')->default(1)->after('first_name');
            $table->timestamp('date_joined')->useCurrent()->after('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Удаление колонок, если нужно откатить миграцию
            $table->dropColumn([
                'username',
                'city',
                'car_model',
                'vin',
                'confirmation_code',
                'sender_id',
                'receiver_id',
                'user_status',
                'is_staff',
                'is_superuser',
                'last_login',
                'last_name',
                'first_name',
                'is_active',
                'date_joined',
            ]);
        });
    }
}
