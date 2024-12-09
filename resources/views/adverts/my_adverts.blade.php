@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/my_adverts.css') }}"> <!-- Подключение основного CSS-файла -->
<script src="{{ asset('js/my_adverts.js') }}" defer></script>
@include('components.header-seller')

<div class="container mx-auto p-4 bg-white rounded shadow-md overflow-x-auto mt-20">
    <!-- Форма поиска -->
    <form method="GET" action="{{ route('adverts.my_adverts') }}" class="search-form flex flex-wrap items-center mb-4">
        <input type="text" name="search" class="searchInput p-2 border rounded-md mr-2 mb-2 w-full md:w-1/2" placeholder="Поиск по наименованию или номеру" value="{{ request()->input('search') }}">
        <select name="brand" class="brandFilter p-2 border rounded-md mr-2 mb-2 w-full md:w-1/4">
            <option value="">Все марки</option>
            @foreach($brands as $brand)
                <option value="{{ $brand }}" {{ request()->get('brand') == $brand ? 'selected' : '' }}>{{ $brand }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-search p-2 bg-blue-500 text-white rounded-md mr-2 mb-2">Поиск</button>
        <a href="{{ route('adverts.my_adverts') }}" class="btn-reset p-2 bg-gray-300 text-black rounded-md mb-2">Сбросить</a>
    </form>

    <!-- Таблица объявлений -->
    @if ($adverts->isEmpty())
        <p>У вас нет активных объявлений.</p>
    @else
    <div class="overflow-x-auto">
        <table class="table w-full border-collapse">
            <thead>
                <tr>
                    <th class="bg-gray-200 border p-2">Артикул</th>
                    <th class="bg-gray-200 border p-2">Наименование</th>
                    <th class="bg-gray-200 border p-2">Состояние</th>
                    <th class="bg-gray-200 border p-2">Марка</th>
                    <th class="bg-gray-200 border p-2">Модель</th>
                    <th class="bg-gray-200 border p-2">Кузов</th>
                    <th class="bg-gray-200 border p-2">Номер</th>
                    <th class="bg-gray-200 border p-2">Двигатель</th>
                    <th class="bg-gray-200 border p-2">Год</th>
                    <th class="bg-gray-200 border p-2">L/R</th>
                    <th class="bg-gray-200 border p-2">F/R</th>
                    <th class="bg-gray-200 border p-2">U/D</th>
                    <th class="bg-gray-200 border p-2">Цена</th>
                    <th class="bg-gray-200 border p-2">Цвет</th>
                    <th class="bg-gray-200 border p-2">Применимость/Описание</th>
                    <th class="bg-gray-200 border p-2">Количество</th>
                    <th class="bg-gray-200 border p-2">Наличие</th>
                    <th class="bg-gray-200 border p-2">Время доставки</th>
                    <th class="bg-gray-200 border p-2">Фото</th>
                    <th class="bg-gray-200 border p-2">Действия</th> <!-- Новый столбец для кнопок -->
                </tr>
            </thead>
            <tbody>
                @foreach($adverts as $advert)
                <tr data-id-info="{{ $advert->id }}"
                    data-art-number="{{ $advert->art_number }}"
                    data-product-name="{{ $advert->product_name }}"
                    data-brand-info="{{ $advert->brand }}"
                    data-model-info="{{ $advert->model }}"
                    data-body-info="{{ $advert->body }}"
                    data-number-info="{{ $advert->number }}"
                    data-engine-info="{{ $advert->engine }}"
                    data-main-photo-url="{{ $advert->main_photo_url }}"
                    data-additional-photo-url-1="{{ $advert->additional_photo_url_1 }}"
                    data-additional-photo-url-2="{{ $advert->additional_photo_url_2 }}"
                    data-additional-photo-url-3="{{ $advert->additional_photo_url_3 }}"
                    data-price-info="{{ $advert->price }}">
                    <td class="border p-2">{{ $advert->art_number }}</td>
                    <td class="border p-2">{{ $advert->product_name }}</td>
                    <td class="border p-2">{{ $advert->new_used }}</td>
                    <td class="border p-2">{{ $advert->brand }}</td>
                    <td class="border p-2">{{ $advert->model }}</td>
                    <td class="border p-2">{{ $advert->body }}</td>
                    <td class="border p-2">{{ $advert->number }}</td>
                    <td class="border p-2">{{ $advert->engine }}</td>
                    <td class="border p-2">{{ $advert->year }}</td>
                    <td class="border p-2">{{ $advert->L_R }}</td>
                    <td class="border p-2">{{ $advert->F_R }}</td>
                    <td class="border p-2">{{ $advert->U_D }}</td>
                    <td class="border p-2">{{ $advert->price }}</td>
                    <td class="border p-2">{{ $advert->color }}</td>
                    <td class="border p-2">{{ $advert->applicability }}</td>
                    <td class="border p-2">{{ $advert->quantity }}</td>
                    <td class="border p-2">{{ $advert->availability }}</td>
                    <td class="border p-2">{{ $advert->delivery_time }}</td>
                    <td class="border p-2"><img src="{{ $advert->main_photo_url }}" alt="Фото" class="w-12 h-12"></td>
                    <td class="border p-2">
                        <button class="btn btn-primary edit-btn p-2 bg-blue-500 text-white rounded-md mr-2" data-id="{{ $advert->id }}">Редактировать</button>
                        <form action="{{ route('adverts.destroy', $advert->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger p-2 bg-red-500 text-white rounded-md" onclick="return confirm('Вы уверены?')">Удалить</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <!-- Подключение пагинации -->
    @include('components.pagination', ['adverts' => $adverts])
    @endif
</div>

<!-- Модальное окно для редактирования -->
<div id="editModal" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="modal-content bg-white p-4 rounded-lg w-full md:w-3/4 lg:w-1/2">
        <span class="close text-gray-500 text-2xl font-bold float-right cursor-pointer">&times;</span>
        <h2 class="text-xl font-bold mb-4">Редактировать объявление</h2>
        <form id="editForm" action="{{ route('adverts.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" id="editAdvertId" name="id">
            <input type="hidden" id="old_art_number" name="old_art_number">
            <input type="hidden" id="old_product_name" name="old_product_name">
            <input type="hidden" id="old_number" name="old_number">
            <input type="hidden" id="old_new_used" name="old_new_used">
            <input type="hidden" id="old_brand" name="old_brand">
            <input type="hidden" id="old_model" name="old_model">
            <input type="hidden" id="old_year" name="old_year">
            <input type="hidden" id="old_body" name="old_body">
            <input type="hidden" id="old_engine" name="old_engine">
            <input type="hidden" id="old_L_R" name="old_L_R">
            <input type="hidden" id="old_F_R" name="old_F_R">
            <input type="hidden" id="old_U_D" name="old_U_D">
            <input type="hidden" id="old_color" name="old_color">
            <input type="hidden" id="old_applicability" name="old_applicability">
            <input type="hidden" id="old_quantity" name="old_quantity">
            <input type="hidden" id="old_price" name="old_price">
            <input type="hidden" id="old_availability" name="old_availability">
            <input type="hidden" id="old_main_photo_url" name="old_main_photo_url">
            <input type="hidden" id="old_additional_photo_url_1" name="old_additional_photo_url_1">
            <input type="hidden" id="old_additional_photo_url_2" name="old_additional_photo_url_2">
            <input type="hidden" id="old_additional_photo_url_3" name="old_additional_photo_url_3">

            <div class="form-group mb-4">
                <label for="art_number" class="block text-gray-700">Артикул</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="art_number" name="art_number">
            </div>

            <div class="form-group mb-4">
                <label for="product_name" class="block text-gray-700">Название товара</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="product_name" name="product_name">
            </div>

            <div class="form-group mb-4">
                <label for="number" class="block text-gray-700">Номер детали</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="number" name="number">
            </div>

            <div class="form-group mb-4">
                <label for="new_used" class="block text-gray-700">Состояние</label>
                <select class="form-control p-2 border rounded-md w-full" id="new_used" name="new_used">
                    <option value="new">Новый</option>
                    <option value="used">Б/У</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="brand" class="block text-gray-700">Марка</label>
                <select id="brand" name="brand" data-url="{{ route('get.models') }}" class="form-control p-2 border rounded-md w-full">
                    <option value="">Выберите марку</option>
                    @foreach(App\Models\BaseAvto::distinct()->pluck('brand') as $brand)
                        <option value="{{ $brand }}" {{ request()->get('brand') == $brand ? 'selected' : '' }}>
                            {{ $brand }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="model" class="block text-gray-700">Модель</label>
                <select id="model" name="model" class="form-control p-2 border rounded-md w-full">
                    <option value="">Выберите модель</option>
                    @if(request()->get('brand')) 
                        @foreach(App\Models\BaseAvto::where('brand', request()->get('brand'))->distinct()->pluck('model') as $model)
                            <option value="{{ $model }}" {{ request()->get('model') == $model ? 'selected' : '' }}>
                                {{ $model }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="year" class="block text-gray-700">Год выпуска</label>
                <select id="year" name="year" class="form-control p-2 border rounded-md w-full">
                    <option value="">Выберите год выпуска</option>
                    @for($i = 2000; $i <= date('Y'); $i++)
                        <option value="{{ $i }}" {{ request()->get('year') == $i ? 'selected' : '' }}>
                            {{ $i }}
                        </option>
                    @endfor
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="body" class="block text-gray-700">Модель Кузова</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="body" name="body">
            </div>

            <div class="form-group mb-4">
                <label for="engine" class="block text-gray-700">Модель Двигателя</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="engine" name="engine">
            </div>

            <div class="form-group mb-4">
                <label for="L_R" class="block text-gray-700">Слева/Справа</label>
                <select class="form-control p-2 border rounded-md w-full" id="L_R" name="L_R">
                    <option value="">Выберите расположение</option>
                    <option value="Слева">Слева (L)</option>
                    <option value="Справа">Справа (R)</option>
                </select>
            </div>

            <div class="form-group mb-4">
                <label for="F_R" class="block text-gray-700">Спереди/Сзади</label>
                <select class="form-control p-2 border rounded-md w-full" id="F_R" name="F_R">
                    <option value="">Выберите расположение</option>
                    <option value="Спереди">Спереди (F)</option>
                    <option value="Сзади">Сзади (R)</option>
                </select>        
            </div>

            <div class="form-group mb-4">
                <label for="U_D" class="block text-gray-700">Сверху/Снизу</label>
                <select class="form-control p-2 border rounded-md w-full" id="U_D" name="U_D">
                    <option value="">Выберите расположение</option>
                    <option value="Сверху">Сверху (U)</option>
                    <option value="Снизу">Снизу (D)</option>
                </select>         
            </div>

            <div class="form-group mb-4">
                <label for="color" class="block text-gray-700">Цвет</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="color" name="color">
            </div>

            <div class="form-group mb-4">
                <label for="applicability" class="block text-gray-700">Применимость</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="applicability" name="applicability">
            </div>

            <div class="form-group mb-4">
                <label for="quantity" class="block text-gray-700">Количество</label>
                <input type="number" class="form-control p-2 border rounded-md w-full" id="quantity" name="quantity" min="1">
            </div>

            <div class="form-group mb-4">
                <label for="price" class="block text-gray-700">Цена</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="price" name="price" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            </div>

            <div class="form-group mb-4">
                <label for="availability" class="block text-gray-700">Наличие</label>
                <select class="form-control p-2 border rounded-md w-full" id="availability" name="availability">
                    <option value="1">В наличии</option>
                    <option value="0">Нет в наличии</option>
                </select>
            </div>

            <!-- Добавление полей для URL фотографий -->
            <div class="form-group mb-4">
                <label for="main_photo_url" class="block text-gray-700">Основное фото (URL)</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="main_photo_url" name="main_photo_url">
            </div>

            <div class="form-group mb-4">
                <label for="additional_photo_url_1" class="block text-gray-700">Дополнительное фото 1 (URL)</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="additional_photo_url_1" name="additional_photo_url_1">
            </div>

            <div class="form-group mb-4">
                <label for="additional_photo_url_2" class="block text-gray-700">Дополнительное фото 2 (URL)</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="additional_photo_url_2" name="additional_photo_url_2">
            </div>

            <div class="form-group mb-4">
                <label for="additional_photo_url_3" class="block text-gray-700">Дополнительное фото 3 (URL)</label>
                <input type="text" class="form-control p-2 border rounded-md w-full" id="additional_photo_url_3" name="additional_photo_url_3">
            </div>

            <button type="submit" class="btn btn-primary p-2 bg-blue-500 text-white rounded-md">Сохранить</button>
        </form>
    </div>
</div>

<!-- Модальное окно (окно подробностями о товаре) -->
<div id="viewModal" class="modal fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="modal-content bg-white p-4 rounded-lg w-full md:w-3/4 lg:w-1/2">
        <span class="close text-gray-500 text-2xl font-bold float-right cursor-pointer">&times;</span>
        <div class="modal-images flex flex-wrap items-center">
            <img id="modalMainImg" src="" class="w-full md:w-1/2 h-auto object-cover mb-4">
            <div class="additional-images flex flex-col w-full md:w-1/2">
                <img id="modalAdditionalImg1" src="" class="w-full h-auto object-cover mb-2">
                <img id="modalAdditionalImg2" src="" class="w-full h-auto object-cover mb-2">
                <img id="modalAdditionalImg3" src="" class="w-full h-auto object-cover mb-2">
            </div>
        </div>
        <p id="modalInfo" class="mt-4"></p>
        <div class="addToCartContainer mt-4">
            <button id="addToCartBtn" class="p-2 bg-blue-500 text-white rounded-md">Добавить в корзину</button>
            <p id="cartNotification" class="text-sm mt-2"></p>
        </div>
    </div>
</div>

@endsection