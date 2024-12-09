<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAdvertsTable extends Migration
{
    /**
     * Запустите миграцию.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Удаление временных меток, если они существуют
            // $table->dropTimestamps(); // Если вы хотите удалить временные метки
            
            // Добавление поля 'status_ad' с значением по умолчанию 'acniv'
            if (!Schema::hasColumn('adverts', 'status_ad')) {
                $table->enum('status_ad', ['activ', 'inactive'])->default('acniv')->after('id_ad');
            }

            // Добавление поля 'data' типа datetime, если оно отсутствует
            if (!Schema::hasColumn('adverts', 'data')) {
                $table->dateTime('data')->nullable()->after('photo');
            }

            // Обновление поля 'quantity' с приведением к типу string, если это необходимо
            // Важно: Приведение типов может потребовать дополнительной обработки данных
            // В этом случае лучше создать новое поле и перенести данные, если необходимо

            // Обновление других полей, если требуется
            // Например, если нужно изменить тип данных или добавить ограничения
        });
    }

    /**
     * Обратная миграция.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('adverts', function (Blueprint $table) {
            // Удаление поля 'status_ad', если оно было добавлено
            if (Schema::hasColumn('adverts', 'status_ad')) {
                $table->dropColumn('status_ad');
            }

            // Удаление поля 'data', если оно было добавлено
            if (Schema::hasColumn('adverts', 'data')) {
                $table->dropColumn('data');
            }
        });
    }
}
