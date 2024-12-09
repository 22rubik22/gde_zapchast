<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анализ рынка</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="font-sans">
@include('components.header-seller')

<div class="container mx-auto p-4 mt-40 mb-20">
    <h2 class="text-2xl font-bold mb-4 pl-6">Анализ рынка</h2>
    <div class="ad-list relative p-4">
        <form id="searchForm" action="{{ route('market.analysis') }}" method="GET" class="flex flex-wrap items-center bg-white p-4 rounded-lg shadow-lg">
            <div class="form-group w-full md:w-1/2 pr-2">
                <input type="text" class="form-control w-full p-2 border rounded-md" id="part_name_or_number" name="part_name_or_number" placeholder="Введите название или номер детали" value="{{ $productName ?? '' }}">
            </div>
            <div class="form-group w-full md:w-1/2 pl-2">
                <input type="text" class="form-control w-full p-2 border rounded-md" id="brand" name="brand" placeholder="Введите марку" value="{{ $brand ?? '' }}">
            </div>
            <button type="submit" class="btn btn-primary bg-blue-500 text-white p-2 rounded-md mt-4 w-full md:w-auto md:mt-0">Поиск</button>
        </form>
    </div>

    @if(isset($productName) && isset($brand))
        <!-- Ссылка с якорем для перемещения на блок статистики -->
        <a href="#statistic" class="btn btn-secondary bg-gray-500 text-white p-2 rounded-md mt-4 block w-full md:w-auto">Перейти к статистике цен</a>

        @if(isset($userAdverts) && $userAdverts->count() > 0)
            <h4 class="text-xl font-bold mt-4 pl-6">Мои товары</h4>
            @foreach($userAdverts as $advert)
                <div class="advert-block bg-white border border-gray-300 rounded-lg p-4 shadow-md cursor-pointer transition-colors duration-300 hover:bg-blue-100" onclick="location.href='{{ route('adverts.show', $advert->id) }}'" tabindex="0" role="button">
                    <div class="advert-details flex gap-4">
                        <!-- Вывод главного фото -->
                        @if ($advert->main_photo_url)
                            <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="advert-main-photo w-24 h-24 object-cover rounded-lg">
                        @endif
                        <div class="flex-1">
                            <strong>ID:</strong> {{ $advert->id }}<br>
                            <strong>Название продукта:</strong> {{ $advert->product_name }}<br>
                            <strong>Цена:</strong> {{ $advert->price }} ₽<br>
                            <strong>Статус:</strong> {{ $advert->status_ad }}<br>
                            <strong>Город:</strong> {{ $advert->user->userAddress->city ?? 'Не указан' }}<br>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="mt-4">Нет результатов для "{{ $productName }}" и "{{ $brand }}" среди ваших товаров.</p>
        @endif

        @if(isset($competitorAdverts) && $competitorAdverts->count() > 0)
            <h4 class="text-xl font-bold mt-4 pl-6">Товары конкурентов</h4>
            @foreach($competitorAdverts as $advert)
                <div class="advert-block bg-white border border-gray-300 rounded-lg p-4 shadow-md cursor-pointer transition-colors duration-300 hover:bg-blue-100" onclick="location.href='{{ route('adverts.show', $advert->id) }}'" tabindex="0" role="button">
                    <div class="advert-details flex gap-4">
                        <!-- Вывод главного фото -->
                        @if ($advert->main_photo_url)
                            <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="advert-main-photo w-24 h-24 object-cover rounded-lg">
                        @endif
                        <div class="flex-1">
                            <strong>ID:</strong> {{ $advert->id }}<br>
                            <strong>Название продукта:</strong> {{ $advert->product_name }}<br>
                            <strong>Цена:</strong> {{ $advert->price }} ₽<br>
                            <strong>Статус:</strong> {{ $advert->status_ad }}<br>
                            <strong>Город:</strong> {{ $advert->user->userAddress->city ?? 'Не указан' }}<br>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="mt-4">Нет результатов для "{{ $productName }}" и "{{ $brand }}" среди товаров конкурентов.</p>
        @endif

        @if(isset($minPrice) && isset($maxPrice) && isset($avgPrice))
            <div class="statistic bg-gray-100 border border-gray-300 rounded-lg p-4 shadow-md mt-4" id="statistic">
                <h3 class="text-xl font-bold mb-4">Статистика цен</h3>
                <p>Минимальная цена: <span class="min-price text-green-600">{{ $minPrice }} ₽</span></p>
                <p>Средняя цена: <span class="avg-price text-orange-600">{{ $avgPrice }} ₽</span></p>
                <p>Максимальная цена: <span class="max-price text-red-600">{{ $maxPrice }} ₽</span></p>
            </div>
        @endif
    @endif
</div>

@extends('layouts.app')

@section('content')
    <!-- Здесь можно добавить дополнительный контент для страницы анализа рынка -->
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Проверяем, есть ли результаты поиска на странице
        const filteredAdverts = document.querySelector('.container h3:first-of-type');

        if (filteredAdverts) {
            // Сброс значений полей формы после вывода результатов поиска
            document.getElementById('part_name_or_number').value = '';
            document.getElementById('brand').value = '';
        }

        // Плавный скролл по якорю
        const links = document.querySelectorAll('a[href^="#"]');
        links.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();

                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);

                if (targetElement) {
                    window.scrollTo({
                        top: targetElement.offsetTop,
                        behavior: 'smooth'
                    });
                }
            });
        });
    });
</script>
</body>
</html>