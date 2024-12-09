<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $advert->product_name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=9fbfa4df-7869-44a3-ae8e-0ebc49545ea9" type="text/javascript"></script>
<script>
    ymaps.ready(init);

    function init() {
        var myMap = new ymaps.Map('map', {
            center: [52.753994, 104.622093],
            zoom: 9, 
            controls: []
        });

        // Данные для геокодирования
        var address = @json($address_line);
        var prod_name = @json($product_name);
        var image_url = @json($main_photo_url);
        var advert_id = @json($advert->id);

        // URL изображения по умолчанию
        var defaultImageUrl = "{{ asset('images/dontfoto.jpg') }}";

        // Функция для геокодирования и добавления метки на карту
        function geocodeAndAddToMap(address, prod_name, image_url, advert_id) {
            if (!address || address === "Не указан") {
                return; // Пропускаем добавление метки, если адрес отсутствует
            }

            ymaps.geocode(address, {
                results: 1
            }).then(function (res) {
                var firstGeoObject = res.geoObjects.get(0),
                    coords = firstGeoObject.geometry.getCoordinates(),
                    bounds = firstGeoObject.properties.get('boundedBy');

                // Проверяем, существует ли URL изображения
                var imageUrl = image_url ? image_url : defaultImageUrl;

                // Создаем метку с пользовательским контентом
                var placemark = new ymaps.Placemark(coords, {
                    balloonContent: address + '<br><a href="{{ route('adverts.show', '') }}/' + advert_id + '">' + prod_name + '</a><br><img src="' + imageUrl + '" alt="Фото отсутствует" width="100">', // Пользовательский контент в баллуне с изображением и ссылкой
                    hintContent: prod_name // Пользовательский контент в подсказке
                }, {
                    preset: 'islands#darkBlueDotIconWithCaption'
                });

                myMap.geoObjects.add(placemark);

                // Центрируем карту на последней добавленной метке
                myMap.setCenter(coords, 15, {
                    checkZoomRange: true
                });
            });
        }

        // Выполняем геокодирование и добавление метки для адреса
        geocodeAndAddToMap(address, prod_name, image_url, advert_id);
    }
</script>

<body class="font-sans">
@include('components.header-seller')

<!-- Горизонтальная линия под хэдером -->
<div class="border-b border-gray-300 my-4"></div>

<!-- путь -->
<div class="container_path px-4 py-2">
    <a href="{{ route('adverts.index') }}" class="text-blue-500 hover:underline">Главная</a> /
    <a href="javascript:history.back()" class="text-blue-500 hover:underline">Поиск</a> /
    <a href="{{ route('adverts.show', $advert->id) }}" class="text-blue-500 hover:underline">{{ $advert->product_name }}</a>
</div>

<div class="container mx-auto px-4 py-6 bg-white">
    <h1 class="text-3xl font-bold mb-4">{{ $advert->product_name }}</h1>
    
    <div class="flex flex-wrap -mx-4">
        <!-- Вывод дополнительных фото -->
        <div class="w-full md:w-1/3 px-4 mb-4">
            <div class="space-y-4">
                @if ($advert->main_photo_url)
                    <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="w-full h-auto rounded-lg">
                @else
                    <img src="{{ asset('images/dontfoto.jpg') }}" alt="Default Photo" class="w-full h-auto rounded-lg">
                @endif
                @if ($advert->additional_photo_url_1)
                    <img src="{{ $advert->additional_photo_url_1 }}" alt="{{ $advert->product_name }} - Дополнительное фото 1" class="w-full h-auto rounded-lg">
                @endif
                @if ($advert->additional_photo_url_2)
                    <img src="{{ $advert->additional_photo_url_2 }}" alt="{{ $advert->product_name }} - Дополнительное фото 2" class="w-full h-auto rounded-lg">
                @endif
                @if ($advert->additional_photo_url_3)
                    <img src="{{ $advert->additional_photo_url_3 }}" alt="{{ $advert->product_name }} - Дополнительное фото 3" class="w-full h-auto rounded-lg">
                @endif
            </div>
        </div>

        <div class="w-full md:w-2/3 px-4">
            <p><strong>Продавец:</strong> {{ $advert->user->username }}</p>
            <p><strong>Город:</strong> {{ $advert->user->userAddress->city ?? 'Не указан' }}</p>
            <p><strong>Адрес:</strong> {{ $advert->user->userAddress->address_line ?? 'Не указан' }}</p>

            <p><strong>ID:</strong> {{ $advert->id }}</p>
            <p><strong>Марка:</strong> {{ $advert->brand }}</p>
            <p><strong>Модель:</strong> {{ $advert->model }}</p>
            <p><strong>Год:</strong> {{ $advert->year }}</p>
            <p><strong>Цена:</strong> {{ $advert->price }} ₽</p>
            <p><strong>Статус:</strong> {{ $advert->status_ad }}</p>
            <p><strong>Описание:</strong> {{ $advert->body }}</p>

            @if ($foundPartId && $foundPartName)
                <p><strong>Найдена деталь:</strong> {{ $foundPartName }} (ID: {{ $foundPartId }})</p>
            @else
                <p><strong>Деталь не найдена.</strong></p>
            @endif

            @if ($modificationId)
                <p><strong>ID модификации:</strong> {{ $modificationId }}</p>
            @else
                <p><strong>Модификация не найдена.</strong></p>
            @endif
            
            <!-- Кнопка для создания нового чата -->
            <form action="{{ route('chat.open', ['advert' => $advert]) }}" method="POST" class="mt-3">
                @csrf
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700">Начать чат</button>
            </form>
           
            <!-- Отображение связанных запросов по найденному id_queri -->
            @if ($relatedQueries->isNotEmpty())
                <!-- Таблица с данными автомобилей -->
                <h2 class="text-xl font-bold mt-4">Данные автомобилей</h2>
                <table class="table-auto w-full mt-2">
                    <thead class="bg-gray-200">
                        <tr>
                            <th class="px-4 py-2">Бренд</th>
                            <th class="px-4 py-2">Модель</th>
                            <th class="px-4 py-2">Поколение</th>
                            <th class="px-4 py-2">Период выпуска</th>
                            <th class="px-4 py-2">Модификация</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($relatedCars as $car)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $car['brand'] }}</td>
                                <td class="px-4 py-2">{{ $car['model'] }}</td>
                                <td class="px-4 py-2">{{ $car['generation'] }}</td>
                                <td class="px-4 py-2">{{ $car['year_from'] }} - {{ $car['year_before'] }}</td>
                                <td class="px-4 py-2">{{ $car['modification'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p><strong>Нет данных о применимости</strong></p>
            @endif
        </div>
    </div>

    <div id="map" class="w-full h-96 mt-4 mb-12"></div>
</div>
</body>
</html>