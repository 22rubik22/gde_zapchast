<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Профиль пользователя</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 font-sans">
@include('components.header-seller')

<div class="container mx-auto px-4 py-6 mb-20">
    <h2 class="text-2xl font-bold text-center mb-6">Профиль пользователя</h2>
    
    <div id="user-profile" class="bg-white p-6 rounded-lg shadow-md">
        @if($user->avatar_url)
            <img src="{{ $user->avatar_url }}" alt="Аватар пользователя" class="ava w-24 h-24 rounded-full mx-auto mb-4">
        @else
            <p class="text-center">Аватар не задан.</p>
        @endif

        <p class="mb-2"><strong>Имя пользователя:</strong> {{ $user->username }}</p>
        <p class="mb-2"><strong>Email:</strong> {{ $user->email }}</p>

        @if($user->UserAddress)
            <p class="mb-2"><strong>Город:</strong> {{ $user->UserAddress->city }}</p>
            <p class="mb-2"><strong>Улица:</strong> {{ $user->UserAddress->address_line }}</p>
        @else
            <p class="mb-2">Адрес не указан.</p>
        @endif

        @if($user->UserPhoneNumber)
            <p class="mb-2"><strong>Номер:</strong> {{ $user->UserPhoneNumber->number_1 }}</p>
        @else
            <p class="mb-2">Номер не указан.</p>
        @endif
        <!-- Добавьте другие поля по необходимости -->
    </div>

    <div class="mt-6 flex flex-col space-y-4 sm:flex-row sm:space-y-0 sm:space-x-4">
        
        <!-- Добавляем условие для отображения кнопки "Анализ рынка" -->
        @if($user->user_status == 1)
            <a href="{{ route('market.analysis') }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Анализ рынка</a>
        @endif

        <!-- Кнопка редактирования профиля -->
        <a href="{{ route('profile.edit', $user->id) }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Редактировать профиль</a>
        @if($user->user_status == 1)
            <a href="{{ route('tariff.settings') }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Настроить тариф</a>
            <a href="{{ route('adverts.create') }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700 lg:hidden">Разместить товары</a>
            <a href="{{ route('adverts.my_adverts') }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700  lg   :hidden">Мои товары</a>
            <a href="{{ route('converter_set.edit') }}" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700  lg   :hidden">Настройки конвертера</a>
        @endif
    </div>
</div>
</body>
</html>