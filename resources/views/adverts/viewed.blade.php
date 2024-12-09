<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="{{ asset('css/profile.css') }}">
<link rel="stylesheet" href="{{ asset('css/adverts-index.css') }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вы посмотрели</title>
</head>
<body>
@include('components.header-seller')

@extends('layouts.app')

@section('content')
    <h1>Вы посмотрели</h1>
    @foreach(array_reverse($adverts) as $advert)
        <div class="advert-block" onclick="location.href='{{ route('adverts.show', $advert->id) }}'" tabindex="0" role="button">
            <div class="advert-details">
                <div>
                    <strong>ID:</strong> {{ $advert->id }}<br>
                    <strong>Название продукта:</strong> {{ $advert->product_name }}<br>
                    <strong>Цена:</strong> {{ $advert->price }} ₽<br>
                    <strong>Статус:</strong> {{ $advert->status_ad }}<br>
                    <strong>Город:</strong> {{ $advert->user->userAddress->city ?? 'Не указан' }}<br>
                </div>
            </div>
        </div>
    @endforeach
@endsection

</body>
</html>