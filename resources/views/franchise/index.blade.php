<!DOCTYPE html>
<html lang="ru">
<head>
    @include('components.header-seller')   
    @extends('layouts.app')

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Франшиза</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/franchise.css') }}"> <!-- Подключение основного CSS-файла -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body >
    <div class="container mx-auto p-4 mt-40 mb-20">

        <h2 class="text-2xl font-bold mb-4">Твой город - надежная инвестиция</h2>
        <p class="mb-4">Купить город выгодно. Чем больше игроков в виртуальном городе и в Виртуальной России в целом, тем дороже твой город. 
            Развивай свой город любыми возможными способами и твой город станет дороже в десятки раз. Мы будем помогать.
        </p>

        <h3 class="text-xl font-bold mb-4">К каждому зданию подключены платежные системы.
            Жители города покупают квартиры, машины и другие улучшения в Игре за реальные деньги.
            20% от всех поступлений — Ваши.
            
            Средний ежемесячный чек пополнений — 10 000 ₽ 
        </h3>

        <div class="container mx-auto p-4">
            <h2 class="text-2xl font-bold mb-4">Ваши доходы в месяц</h2>
            <div class="overflow-x-auto">
                <table class="w-full border-collapse border border-gray-300">
                    <thead>
                        <tr>
                            <th class="border border-gray-300 p-2 bg-gray-200 text-center">Кол-во магазинов</th>
                            <th class="border border-gray-300 p-2 bg-gray-200 text-center">Ваши доходы в месяц</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 p-2 text-center">100</td>
                            <td class="border border-gray-300 p-2 text-center">200 000 руб.</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 text-center">200</td>
                            <td class="border border-gray-300 p-2 text-center">400 000 руб.</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 text-center">500</td>
                            <td class="border border-gray-300 p-2 text-center">1 млн. руб.</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 text-center">1 000</td>
                            <td class="border border-gray-300 p-2 text-center">2 млн. руб.</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 text-center">2 000</td>
                            <td class="border border-gray-300 p-2 text-center">4 млн. руб.</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 p-2 text-center">3 000</td>
                            <td class="border border-gray-300 p-2 text-center">6 млн. руб.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    
        <h2 class="text-2xl font-bold mb-4">Город может купить любой человек</h2>
        <p class="mb-4">— Вам не нужны специальные знания, у нас всё просто. Твой город - это реальный бизнес.</p>
        <p class="mb-4">— Вы станете зарабатывать серьезные деньги</p>
        <p class="mb-4">— Бизнес, которым можно управлять не выходя из дома</p>

    </div>
</body>
</html>