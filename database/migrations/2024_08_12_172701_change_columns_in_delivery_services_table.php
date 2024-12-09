<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('delivery_services', function (Blueprint $table) {
            // Убедитесь, что вы изменяете только те столбцы, которые должны быть boolean
            $table->boolean('100СП')->default(false)->change();
            $table->boolean('Доставка')->default(false)->change();
            $table->boolean('ATA')->default(false)->change();
            $table->boolean('Boxberry')->default(false)->change();
            $table->boolean('DPD')->default(false)->change();
            $table->boolean('GTD')->default(false)->change();
            $table->boolean('Кашалот')->default(false)->change();
            $table->boolean('КИТ')->default(false)->change();
            $table->boolean('Алтан')->default(false)->change();
            $table->boolean('Баграм-Сервис')->default(false)->change();
            $table->boolean('Байкал-Сервис')->default(false)->change();
            $table->boolean('Берг')->default(false)->change();
            $table->boolean('ВИТЭКА')->default(false)->change();
            $table->boolean('Восток-Запад')->default(false)->change();
            $table->boolean('ГлавДоставка')->default(false)->change();
            $table->boolean('Гринлайн Сибирь')->default(false)->change();
            $table->boolean('ДВ ТЭК')->default(false)->change();
            $table->boolean('Деловые линии')->default(false)->change();
            $table->boolean('ЖелдорАльянс')->default(false)->change();
            $table->boolean('Желдорэкспедиция')->default(false)->change();
            $table->boolean('Камчатка')->default(false)->change();
            $table->boolean('Курьер Регион')->default(false)->change();
            $table->boolean('Луч')->default(false)->change();
            $table->boolean('Мы с Камчатки')->default(false)->change();
            $table->boolean('Ночной экспресс')->default(false)->change();
            $table->boolean('ПЭК')->default(false)->change();
            $table->boolean('Привоз')->default(false)->change();
            $table->boolean('СДЭК')->default(false)->change();
            $table->boolean('СТЕИЛ')->default(false)->change();
            $table->boolean('Солнечный Магадан')->default(false)->change();
            $table->boolean('ТЭК Босфор')->default(false)->change();
            $table->boolean('Транс-Вектор')->default(false)->change();
            $table->boolean('ТрансТрек ДВ')->default(false)->change();
            $table->boolean('Тройка-ДВ')->default(false)->change();
            $table->boolean('Флагман Амур')->default(false)->change();
            $table->boolean('Форвард')->default(false)->change();
            $table->boolean('Экспресс-Авто')->default(false)->change();
            $table->boolean('Энергия')->default(false)->change();
        });
    }
    
};
