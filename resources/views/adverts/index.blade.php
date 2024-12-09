@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Все товары</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans flex flex-col items-center">
   
@include('components.header-seller')   
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script> 
<!-- Рекламный баннер -->
<h1 class="hidden md:block"></h1>
<img src="{{ asset('images/banner.png') }}"  alt="Реклама" class="banner w-11/12 mx-auto rounded-2xl hidden md:block">
<p class="hidden md:block"></p>

<h2 class="text-2xl font-bold mt-8 mb-4 text-center">Поиск запчастей:</h2>
@include('components.search-form') <!-- Подключение формы поиска -->

<div class="container mx-auto w-full max-w-screen-2xl">        
    @if($adverts->isEmpty())
        <p class="text-center text-lg mt-8">Нет доступных объявлений.</p>
    @else
        @php
            // Фильтруем коллекцию, исключая товар с id 1111
            $filteredAdverts = $adverts->reject(function($advert) {
                return $advert->id == 1111;
            });
        @endphp

        @foreach($filteredAdverts as $advert)
    <div class="advert-block w-full md:w-11/12 lg:w-10/12 border border-gray-300 rounded-lg shadow-lg p-4 m-4 cursor-pointer transition-colors duration-300 hover:bg-blue-100" onclick="location.href='{{ route('adverts.show', $advert->id) }}'" tabindex="0" role="button">
        <div class="advert-details flex justify-between gap-4">
            <!-- Вывод главного фото -->
            @if ($advert->main_photo_url)
                <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="advert-main-photo w-52 h-52 object-cover rounded-lg">
            @else
                <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="advert-main-photo w-52 h-52 object-cover rounded-lg">
            @endif
        </div>

        <div class="list mt-4">
            <div class="name flex justify-between items-center text-xl font-bold">
                <span class="list_name">{{ $advert->product_name }}</span>
                <span class="end">{{ $advert->price }} ₽</span>
            </div>
               
            <div class="info flex justify-between items-center mt-2">
                <span class="beg bg-gray-200 px-3 py-1 rounded-lg text-sm">{{ $advert->number}}</span>
                <span class="end text-red-500 text-sm">{{ $advert->user->userAddress->city ?? 'Не указан' }}</span>
            </div>
             
            <div class="car grid grid-cols-4 gap-2 mt-2">
                <span class="bg-yellow-200 px-3 py-1 rounded-lg text-sm">{{ $advert->brand}}</span>
                <span class="bg-yellow-200 px-3 py-1 rounded-lg text-sm">{{ $advert->model}}</span>
                <span class="bg-yellow-200 px-3 py-1 rounded-lg text-sm">{{ $advert->body}}</span>
                <span class="bg-yellow-200 px-3 py-1 rounded-lg text-sm">{{ $advert->engine}}</span>
            </div>
        </div>
    </div>
@endforeach
    <div class="h-24">
        @include('components.pagination', ['adverts' => $adverts])
    </div>
        <!-- Подключение пагинации -->
        
    @endif
</div>
</body>
</html>
@endsection