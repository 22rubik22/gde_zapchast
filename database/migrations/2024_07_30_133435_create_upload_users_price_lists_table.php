<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUploadUsersPriceListsTable extends Migration
{
    /**
     * Запустите миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upload_users_price_lists', function (Blueprint $table) {
            $table->id(); // Создает поле id с автоинкрементом
            $table->string('file_name'); // Поле для имени файла
            $table->text('file_content'); // Поле для содержимого файла
            $table->unsignedBigInteger('user_id'); // Поле для идентификатора пользователя
            $table->string('file_path'); // Поле для пути к файлу
            $table->timestamps(); // Создает поля created_at и updated_at
        });
    }

    /**
     * Откатите миграцию.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('upload_users_price_lists');
    }
}
