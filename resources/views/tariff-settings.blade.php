<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Настройки тарифа</title>
</head>
<body class="flex justify-center flex-col">
@include('components.header-seller')
@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 mt-40 mb-20">
    <h1 class="text-3xl font-bold text-center mb-6">Выберите количество товаров:</h1>
    
    <div class="slider-container">
        <label for="ad-count-manual" class="block text-lg font-bold mb-2">Количество товаров:</label>
        <input type="number" id="ad-count-manual" min="100" max="100000" step="100" value="100" class="w-1/4 p-2 border rounded-md">
        <input type="range" id="ad-count" name="ad-count" min="100" max="100000" step="100" class="w-3/4">
        <span id="ad-count-value" class="inline-block ml-2 font-bold"></span>
    </div>

    <div class="price-container mt-6">
        <p class="mb-2">Стоимость размещения в день: <span id="daily-cost" class="text-green-600">₽0.00</span></p>
        <p class="mb-2">Стоимость размещения в день одного товара: <span id="daily-cost-per-item" class="text-green-600">₽0.00</span></p>
        <p class="mb-4">Стоимость размещения в месяц: <span id="monthly-cost" class="text-green-600">₽0.00</span></p>
        <button id="save-button" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Сохранить</button>
    </div>

    <div class="payment-container mt-6" style="display: none;">
        <p class="mb-4">Сумма к оплате: <span id="payment-amount" class="text-green-600">₽0.00</span></p>
        <button id="pay-button-card" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Оплатить картой</button>
        <button id="pay-button-card" class="bg-blue-500 text-white px-4 py-2 rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Оплатить со счета</button>
    </div>
</div>

<h3 class="text-xl font-bold text-center mt-6">Нужно разместить больше 100.000 товаров?</h3>
<h3 class="text-xl font-bold text-center">Напишите нам и мы подготовим для Вас персональное предложение</h3>

<script>
    const slider = document.getElementById('ad-count');
    const manualInput = document.getElementById('ad-count-manual');
    const dailyCost = document.getElementById('daily-cost');
    const dailyCostPerItem = document.getElementById('daily-cost-per-item');
    const monthlyCost = document.getElementById('monthly-cost');
    const saveButton = document.getElementById('save-button');
    const paymentContainer = document.querySelector('.payment-container');
    const paymentAmount = document.getElementById('payment-amount');
    const payButtonCard = document.getElementById('pay-button-card');

    slider.oninput = function() {
        manualInput.value = this.value;
        updatePrices(this.value);
    }

    manualInput.oninput = function() {
        slider.value = this.value;
        updatePrices(this.value);
    }

    function updatePrices(adCount) {
        // Пример расчета цен в рублях
        const basePricePerDay = 0.75; // Базовая стоимость за одно объявление в день в рублях
        const discountFactor = 1 - Math.min(0.5, (adCount - 100) / 100000); // Дисконтный фактор

        const dailyCostValue = adCount * basePricePerDay * discountFactor;
        const dailyCostPerItemValue = basePricePerDay * discountFactor;
        const monthlyCostValue = dailyCostValue * 30;

        dailyCost.textContent = `₽${dailyCostValue.toFixed(2)}`;
        dailyCostPerItem.textContent = `₽${dailyCostPerItemValue.toFixed(2)}`;
        monthlyCost.textContent = `₽${monthlyCostValue.toFixed(2)}`;
    }

    saveButton.addEventListener('click', function() {
        const adCount = slider.value;
        const basePricePerDay = 0.75;
        const discountFactor = 1 - Math.min(0.5, (adCount - 100) / 100000);
        const dailyCostValue = adCount * basePricePerDay * discountFactor;
        const monthlyCostValue = dailyCostValue * 30;

        paymentAmount.textContent = `₽${monthlyCostValue.toFixed(2)}`;
        paymentContainer.style.display = 'block';
    });

    payButtonCard.addEventListener('click', function() {
        window.location.href = '/pay';
    });
</script>
@endsection

</body>
</html>