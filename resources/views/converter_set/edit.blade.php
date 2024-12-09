@extends('layouts.app')
@include('components.header-seller')
@section('content')
<div class="container mx-auto p-4 mt-40 mb-20">
    <h4 class="text-xl font-bold mb-4">Настройки конвертера</h4>

    @if(session('success'))
        <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <h5 class="text-lg font-bold mb-4">Выберите марки автомобилей которые есть в Вашем прайс-листе</h5>

    <form action="{{ route('converter_set.update') }}" method="POST" class="space-y-4">
        @csrf
        @method('PUT')

        @foreach ([
            'acura',
            'alfa_romeo',
            'asia',
            'aston_martin',
            'audi',
            'bentley',
            'bmw',
            'byd',
            'cadillac',
            'changan',
            'chevrolet',
            'citroen',
            'daewoo',
            'daihatsu',
            'datsun',
            'fiat',
            'ford',
            'gaz',
            'geely',
            'haval',
            'honda',
            'hyundai',
            'infiniti',
            'isuzu',
            'jaguar',
            'jeep',
            'kia',
            'lada',
            'land_rover',
            'mazda',
            'mercedes_benz',
            'mitsubishi',
            'nissan',
            'opel',
            'peugeot',
            'peugeot_lnonum',
            'porsche',
            'renault',
            'skoda',
            'ssangyong',
            'subaru', 
            'suzuki', 
            'toyota', 
            'uaz', 
            'volkswagen', 
            'volvo', 
            'zaz'
        ] as $brand)
            <div class="flex items-center space-x-2">
                <input type="hidden" name="{{ $brand }}" value="0">
                <input class="form-check-input" type="checkbox" name="{{ $brand }}" id="{{ $brand }}" value="1" {{ isset($converterSet) && $converterSet->$brand ? 'checked' : '' }}>
                <label class="form-check-label" for="{{ $brand }}">{{ ucfirst(str_replace('_', ' ', $brand)) }}</label>
            </div>
        @endforeach

        <h5 class="text-lg font-bold mb-4">Введите названия столбцов Вашего прайс-листа. Названия столбцов которых нет в Вашем прайс-листе оставьте пустыми</h5>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="product_name" class="block text-sm font-medium text-gray-700">Название продукта</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="product_name" id="product_name" value="{{ old('product_name', $converterSet->product_name ?? '') }}">
            </div>

            <div class="form-group">
                <label for="price" class="block text-sm font-medium text-gray-700">Цена</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="price" id="price" value="{{ old('price', $converterSet->price ?? '') }}">
            </div>

            <div class="form-group">
                <label for="car_brand" class="block text-sm font-medium text-gray-700">Марка автомобиля</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="car_brand" id="car_brand" value="{{ old('car_brand', $converterSet->car_brand ?? '') }}">
            </div>

            <div class="form-group">
                <label for="car_model" class="block text-sm font-medium text-gray-700">Модель автомобиля</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="car_model" id="car_model" value="{{ old('car_model', $converterSet->car_model ?? '') }}">
            </div>

            <div class="form-group">
                <label for="year" class="block text-sm font-medium text-gray-700">Год</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="year" id="year" value="{{ old('year', $converterSet->year ?? '') }}">
            </div>

            <div class="form-group">
                <label for="oem_number" class="block text-sm font-medium text-gray-700">OEM номер</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="oem_number" id="oem_number" value="{{ old('oem_number', $converterSet->oem_number ?? '') }}">
            </div>

            <div class="form-group">
                <label for="picture" class="block text-sm font-medium text-gray-700">Изображение</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="picture" id="picture" value="{{ old('picture', $converterSet->picture ?? '') }}">
            </div>

            <div class="form-group">
                <label for="body" class="block text-sm font-medium text-gray-700">Кузов</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="body" id="body" value="{{ old('body', $converterSet->body ?? '') }}">
            </div>

            <div class="form-group">
                <label for="engine" class="block text-sm font-medium text-gray-700">Двигатель</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="engine" id="engine" value="{{ old('engine', $converterSet->engine ?? '') }}">
            </div>

            <div class="form-group">
                <label for="quantity" class="block text-sm font-medium text-gray-700">Количество</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="quantity" id="quantity" value="{{ old('quantity', $converterSet->quantity ?? '') }}">
            </div>

            <div class="form-group">
                <label for="text_declaration" class="block text-sm font-medium text-gray-700">Текст декларации</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="text_declaration" id="text_declaration" value="{{ old('text_declaration', $converterSet->text_declaration ?? '') }}">
            </div>

            <div class="form-group">
                <label for="left_right" class="block text-sm font-medium text-gray-700">Лево/Право</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="left_right" id="left_right" value="{{ old('left_right', $converterSet->left_right ?? '') }}">
            </div>

            <div class="form-group">
                <label for="up_down" class="block text-sm font-medium text-gray-700">Вверх/Вниз</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="up_down" id="up_down" value="{{ old('up_down', $converterSet->up_down ?? '') }}">
            </div>

            <div class="form-group">
                <label for="front_back" class="block text-sm font-medium text-gray-700">Вперед/Назад</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="front_back" id="front_back" value="{{ old('front_back', $converterSet->front_back ?? '') }}">
            </div>

            <div class="form-group">
                <label for="fileformat_col" class="block text-sm font-medium text-gray-700">Формат файла</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="fileformat_col" id="fileformat_col" value="{{ old('fileformat_col', $converterSet->fileformat_col ?? '') }}">
            </div>

            <div class="form-group">
                <label for="encoding" class="block text-sm font-medium text-gray-700">Кодировка</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="encoding" id="encoding" value="{{ old('encoding', $converterSet->encoding ?? '') }}">
            </div>

            <div class="form-group">
                <label for="art_number" class="block text-sm font-medium text-gray-700">Артикул</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="art_number" id="art_number" value="{{ old('art_number', $converterSet->art_number ?? '') }}">
            </div>

            <div class="form-group">
                <label for="availability" class="block text-sm font-medium text-gray-700">Наличие</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="availability" id="availability" value="{{ old('availability', $converterSet->availability ?? '') }}">
            </div>

            <div class="form-group">
                <label for="color" class="block text-sm font-medium text-gray-700">Цвет</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="color" id="color" value="{{ old('color', $converterSet->color ?? '') }}">
            </div>

            <div class="form-group">
                <label for="delivery_time" class="block text-sm font-medium text-gray-700">Время доставки</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="delivery_time" id="delivery_time" value="{{ old('delivery_time', $converterSet->delivery_time ?? '') }}">
            </div>

            <div class="form-group">
                <label for="new_used" class="block text-sm font-medium text-gray-700">Новое/Б/У</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="new_used" id="new_used" value="{{ old('new_used', $converterSet->new_used ?? '') }}">
            </div>
        </div>

        <h5 class="text-lg font-bold mb-4">Параметры файла</h5>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-group">
                <label for="file_price" class="block text-sm font-medium text-gray-700">Адрес файла</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="file_price" id="file_price" value="{{ old('file_price', $converterSet->file_price ?? '') }}">
            </div>

            <div class="form-group">
                <label for="my_file" class="block text-sm font-medium text-gray-700">Мой файл</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="my_file" id="my_file" value="{{ old('my_file', $converterSet->my_file ?? '') }}">
            </div>

            <div class="form-group">
                <label for="header_str_col" class="block text-sm font-medium text-gray-700">Строка заголовка (начасть с [строки])</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="header_str_col" id="header_str_col" value="{{ old('header_str_col', $converterSet->header_str_col ?? '') }}">
            </div>

            <div class="form-group">
                <label for="separator_col" class="block text-sm font-medium text-gray-700">Разделитель колонок</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="separator_col" id="separator_col" value="{{ old('separator_col', $converterSet->separator_col ?? '') }}">
            </div>

            <div class="form-group">
                <label for="del_duplicate" class="block text-sm font-medium text-gray-700">Удалить дубликаты</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="del_duplicate" id="del_duplicate" value="{{ old('del_duplicate', $converterSet->del_duplicate ?? '') }}">
            </div>

            <div class="form-group">
                <label for="many_pages_col" class="block text-sm font-medium text-gray-700">Файл содержит несколько листов (книга Excel)</label>
                <input type="text" class="form-control mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" name="many_pages_col" id="many_pages_col" value="{{ old('many_pages_col', $converterSet->many_pages_col ?? '') }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">Сохранить настройки</button>
    </form>
</div>
@endsection