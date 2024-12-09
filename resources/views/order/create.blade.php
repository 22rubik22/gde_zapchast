<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Оформление заказа</title>
</head>
<body>
@include('components.header-seller')
    <div class="container">
        <h1>Оформление заказа</h1>
        <p>Вы оформляете заказ на товар: {{ $product_name }}</p>
        <p>Марка: {{ $brand }}</p>
        <p>Модель: {{ $model }}</p>
        <p>Год: {{ $year }}</p>
        <p>Цена: {{ $price }} ₽</p>
        <form action="{{ route('order.store') }}" method="POST">
            @csrf
            <input type="hidden" name="advert_id" value="{{ $advert_id }}">
            <input type="hidden" name="seller_id" value="{{ $seller_id }}">
            <button type="submit">Оформить заказ</button>
        </form>
    </div>
</body>
</html>