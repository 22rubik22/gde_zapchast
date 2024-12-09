<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDeliveryServicesTable extends Migration
{
    public function up()
    {
        Schema::create('delivery_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('100СП');
            $table->string('Доставка');
            $table->string('ATA');
            $table->string('Boxberry');
            $table->string('DPD');
            $table->string('GTD');
            $table->string('Кашалот');
            $table->string('КИТ');
            $table->string('Алтан');
            $table->string('Баграм-Сервис');
            $table->string('Байкал-Сервис');
            $table->string('Берг');
            $table->string('ВИТЭКА');
            $table->string('Восток-Запад');
            $table->string('ГлавДоставка');
            $table->string('Гринлайн Сибирь');
            $table->string('ДВ ТЭК');
            $table->string('Деловые линии');
            $table->string('ЖелдорАльянс');
            $table->string('Желдорэкспедиция');
            $table->string('Камчатка');
            $table->string('Курьер Регион');
            $table->string('Луч');
            $table->string('Мы с Камчатки');
            $table->string('Ночной экспресс');
            $table->string('ПЭК');
            $table->string('Привоз');
            $table->string('СДЭК');
            $table->string('СТЕИЛ');
            $table->string('Солнечный Магадан');
            $table->string('ТЭК Босфор');
            $table->string('Транс-Вектор');
            $table->string('ТрансТрек ДВ');
            $table->string('Тройка-ДВ');
            $table->string('Флагман Амур');
            $table->string('Форвард');
            $table->string('Экспресс-Авто');
            $table->string('Энергия');
            
            // Добавьте временные метки, если нужно
            $table->timestamps();

            // Добавьте внешний ключ, если необходимо
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('delivery_services');
    }
}

