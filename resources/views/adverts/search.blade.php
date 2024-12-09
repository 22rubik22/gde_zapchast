@extends('layouts.app')

<link rel="stylesheet" href="{{ asset('css/adverts-index.css') }}"> <!-- Подключение основного CSS-файла -->
<script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU&amp;apikey=9fbfa4df-7869-44a3-ae8e-0ebc49545ea9" type="text/javascript"></script>
<script>
    ymaps.ready(init);

    function init() {
        var myMap = new ymaps.Map('map', {
            center: [52.753994, 104.622093],
            zoom: 9, 
            controls: []
        });

        // Массив адресов для геокодирования
        var addresses = @json($addresses);
        var prod_name = @json($prod_name);
        var image_prod = @json($image_prod);
        var advert_ids = @json($advert_ids);

        // URL изображения по умолчанию
        var defaultImageUrl = "{{ asset('images/dontfoto.jpg') }}";

        // Функция для геокодирования и добавления меток на карту
        function geocodeAndAddToMap(address, prod_name, image_url, advert_id) {
            if (address=="Не указан") {
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
                myMap.setCenter(coords, 10, {
                    checkZoomRange: true
                });
            });
        }

        // Выполняем геокодирование и добавление меток для каждого адреса
        addresses.forEach(function (address, index) {
            geocodeAndAddToMap(address, prod_name[index], image_prod[index], advert_ids[index]);
        });
    }
</script>
 
@section('content')
    @include('components.header-seller')   

    @include('components.search-form') <!-- Подключение формы поиска -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>
    <div id="map" style="width: 100%; height: 400px"></div>
    <h3 class="resultsearch">Результаты поиска:</h3>

    <!-- Фильтры по параметру engine -->
    <div class="filters">
        <h4>Фильтры по двигателю:</h4>
        <form method="GET" action="{{ route('adverts.search') }}"> <!-- Укажите правильный маршрут для обработки формы -->
            @foreach($engines as $engine)
                <div>
                    <input type="checkbox" name="engines[]" value="{{ $engine }}" id="engine-{{ $engine }}"
                        {{ in_array($engine, request('engines', [])) || !request()->has('engines') ? 'checked' : '' }}> <!-- Сохраняем состояние чекбокса -->
                    <label for="engine-{{ $engine }}">{{ !empty($engine) ? ucfirst($engine) : 'Не указан' }}</label>
                </div>
            @endforeach

            <!-- Сохраняем другие параметры запроса -->
            <input type="hidden" name="search_query" value="{{ request('search_query') }}">
            <input type="hidden" name="brand" value="{{ request('brand') }}">
            <input type="hidden" name="model" value="{{ request('model') }}">
            <input type="hidden" name="year" value="{{ request('year') }}">

            <button type="submit">Применить фильтры</button>
        </form>
    </div>

    <div class="container">
        @if($adverts->count())
            @foreach($adverts as $advert)
            <div class="advert-block" onclick="location.href='{{ route('adverts.show', $advert->id) }}'" tabindex="0" role="button">
        <div class="advert-details">
            <!-- Вывод главного фото -->
            @if ($advert->main_photo_url)
                <img src="{{ $advert->main_photo_url }}" alt="{{ $advert->product_name }} - Главное фото" class="advert-main-photo">
            @else
                <img src="{{ asset('images/dontfoto.jpg') }}" alt="Фото отсутствует" class="advert-main-photo">
            @endif
        </div>

        <div class="list">
            <div class="name">
            <span class="list_name">{{ $advert->product_name }}</span>
            <span class="end" >{{ $advert->price }} ₽</span>
            </div>
               

               <div class="info">
               <span class="beg">{{ $advert->number}}</span>
               <span class="end">{{ $advert->user->userAddress->city ?? 'Не указан' }}</span>
            </div>
             
             <div class="car">
             <span>{{ $advert->brand}}</span>
             <span>{{ $advert->model}}</span>
             <span>{{ $advert->body}}</span>
             <span>{{ $advert->engine}}</span>


             </div>
            </div>
    </div>
            @endforeach

            <!-- Подключение пагинации -->
            @include('components.pagination', ['adverts' => $adverts])
        @else
            <p>Нет результатов для отображения.</p>
        @endif
    </div>
@endsection