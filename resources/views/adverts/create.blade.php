@extends('layouts.app')
@include('components.header-seller')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/js-cookie/3.0.1/js.cookie.min.js"></script>

@section('content')

<div class="container mx-auto p-4 mt-20">
    <h1 class="text-2xl font-bold mb-4">Как вы хотите добавить товары?</h1>

    <button class="button bg-blue-500 text-white px-4 py-2 rounded-md mb-2" onclick="scrollToForm()" onmouseover="showText('Создавайте товар заполняя форму')" onmouseout="hideText()">Создать товар с помощью формы</button>
    <button class="button bg-green-500 text-white px-4 py-2 rounded-md" onclick="scrollToForm2()" onmouseover="showText('Если у Вас есть прайс-лист Вы можете загрузить все ваши товары используя функцию импорта')" onmouseout="hideText()">Загрузить товары из прайс-листа</button>
    
    <p id="hoverText" class="text-gray-600 mt-2" style="display: none;"></p>
</div>

<div class="container mx-auto p-4" id="create-product-form">
    <h2 class="text-xl font-bold mb-4">Создать новый товар с помощью формы</h2>

    @if ($errors->any())
        <div class="alert alert-danger bg-red-100 text-red-700 p-4 rounded-md mb-4">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('adverts.store') }}" method="POST">
        @csrf

        <div class="form-group mb-4">
            <label for="art_number" class="block text-gray-700">Артикул</label>
            <input type="text" class="form-control border p-2 w-full" id="art_number" name="art_number">
        </div>

        <div class="form-group mb-4">
            <label for="product_name" class="block text-gray-700">Название товара</label>
            <input type="text" class="form-control border p-2 w-full" id="product_name" name="product_name">
        </div>

        <div class="form-group mb-4">
            <label for="number" class="block text-gray-700">Номер детали</label>
            <input type="text" class="form-control border p-2 w-full" id="number" name="number">
        </div>

        <div class="form-group mb-4">
            <label for="new_used" class="block text-gray-700">Состояние</label>
            <select class="form-control border p-2 w-full" id="new_used" name="new_used">
                <option value="new">Новый</option>
                <option value="used">Б/У</option>
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="brand" class="block text-gray-700">Марка</label>
            <select id="brand" name="brand" data-url="{{ route('get.models') }}" class="form-control border p-2 w-full">
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
            <select id="model" name="model" class="form-control border p-2 w-full">
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
            <select id="year" name="year" class="form-control border p-2 w-full">
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
            <input type="text" class="form-control border p-2 w-full" id="body" name="body">
        </div>

        <div class="form-group mb-4">
            <label for="engine" class="block text-gray-700">Модель Двигателя</label>
            <input type="text" class="form-control border p-2 w-full" id="engine" name="engine">
        </div>

        <div class="form-group mb-4">
            <label for="L_R" class="block text-gray-700">Слева/Справа</label>
            <select class="form-control border p-2 w-full" id="L_R" name="L_R">
                <option value="">Выберите расположение</option>
                <option value="Слева">Слева (L)</option>
                <option value="Справа">Справа (R)</option>
            </select>
        </div>

        <div class="form-group mb-4">
            <label for="F_R" class="block text-gray-700">Спереди/Сзади</label>
            <select class="form-control border p-2 w-full" id="F_R" name="F_R">
                <option value="">Выберите расположение</option>
                <option value="Спереди">Спереди (F)</option>
                <option value="Сзади">Сзади (R)</option>
            </select>        
        </div>

        <div class="form-group mb-4">
            <label for="U_D" class="block text-gray-700">Сверху/Снизу</label>
            <select class="form-control border p-2 w-full" id="U_D" name="U_D">
                <option value="">Выберите расположение</option>
                <option value="Сверху">Сверху (U)</option>
                <option value="Снизу">Снизу (D)</option>
            </select>         
        </div>

        <div class="form-group mb-4">
            <label for="color" class="block text-gray-700">Цвет</label>
            <input type="text" class="form-control border p-2 w-full" id="color" name="color">
        </div>

        <div class="form-group mb-4">
            <label for="applicability" class="block text-gray-700">Применимость</label>
            <input type="text" class="form-control border p-2 w-full" id="applicability" name="applicability">
        </div>

        <div class="form-group mb-4">
            <label for="quantity" class="block text-gray-700">Количество</label>
            <input type="number" class="form-control border p-2 w-full" id="quantity" name="quantity" min="1">
        </div>

        <div class="form-group mb-4">
            <label for="price" class="block text-gray-700">Цена</label>
            <input type="text" class="form-control border p-2 w-full" id="price" name="price" min="0" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>

        <div class="form-group mb-4">
            <label for="availability" class="block text-gray-700">Наличие</label>
            <select class="form-control border p-2 w-full" id="availability" name="availability">
                <option value="1">В наличии</option>
                <option value="0">Нет в наличии</option>
            </select>
        </div>

        <!-- Добавление полей для URL фотографий -->
        <div class="form-group mb-4">
            <label for="main_photo_url" class="block text-gray-700">Основное фото (URL)</label>
            <input type="text" class="form-control border p-2 w-full" id="main_photo_url" name="main_photo_url">
        </div>

        <div class="form-group mb-4">
            <label for="additional_photo_url_1" class="block text-gray-700">Дополнительное фото 1 (URL)</label>
            <input type="text" class="form-control border p-2 w-full" id="additional_photo_url_1" name="additional_photo_url_1">
        </div>

        <div class="form-group mb-4">
            <label for="additional_photo_url_2" class="block text-gray-700">Дополнительное фото 2 (URL)</label>
            <input type="text" class="form-control border p-2 w-full" id="additional_photo_url_2" name="additional_photo_url_2">
        </div>

        <div class="form-group mb-4">
            <label for="additional_photo_url_3" class="block text-gray-700">Дополнительное фото 3 (URL)</label>
            <input type="text" class="form-control border p-2 w-full" id="additional_photo_url_3" name="additional_photo_url_3">
        </div>

        <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md">Создать товар</button>
    </form>
</div>

<!-- Форма для импорта товаров из файла -->
<div class="container mx-auto p-4 mt-20">
<h2 class="text-xl font-bold mb-4">Конвертировать прайс-лист и загрузить товары</h2>
    <form id="convert-form" action="" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group mb-4">
           
            <label for="file" class="block text-gray-700">Выберите файл для конвертации</label>
         
            <input type="file" name="file" id="file" required class="form-control border p-2 w-full">
        </div>

        <button type="button" id="convert-button" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md">Открыть файл</button>
    </form>
</div>

<!-- Форма для выбора столбцов -->
<div id="columns-form" class="container mx-auto p-4 mt-20" style="display: none;">
    <form id="import-columns-form" action="{{ route('cars.import') }}" method="POST">
        @csrf
        <div class="grid grid-cols-2 gap-4" id="columns-container">   
           
            <h2>Найденные столбцы в вашем файле</h2>
            <h2>Данные которые содержит столбец</h2>
    </div>
    <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md">Конвертировать файл</button>
    </form>
</div>
<h2 class="text-xl font-bold mb-4">Прямой импорт товаров из прайс-листа</h2>

@if ($errors->any())
    <div class="alert alert-danger bg-red-100 text-red-700 p-4 rounded-md mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

@if(session('success'))
    <div class="alert alert-success bg-green-100 text-green-700 p-4 rounded-md mb-4">
        {{ session('success') }}
    </div>
@endif

<form action="{{ route('cars.import') }}" method="POST" enctype="multipart/form-data">
    @csrf

    <div class="form-group mb-4">
        <label for="file" class="block text-gray-700">Выберите файл для импорта</label>
        <input type="file" name="file" required class="form-control border p-2 w-full">
    </div>

    <button type="submit" class="btn btn-primary bg-blue-500 text-white px-4 py-2 rounded-md">Импортировать товары</button>
    <div class="import mt-4">
        <a href="{{ asset('import.xlsx') }}" class="btn btn-secondary bg-gray-500 text-white px-4 py-2 rounded-md" download>Скачать пример файла</a>
    </div>
    
</form>
</div>
@endsection

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/search-form.js') }}" defer></script>

<script>
    function scrollToForm() {
        document.getElementById('create-product-form').scrollIntoView({ behavior: 'smooth' });
    }

    function scrollToForm2() {
        document.getElementById('import-product-form').scrollIntoView({ behavior: 'smooth' });
    }

    function showText(text) {
        document.getElementById('hoverText').textContent = text;
        document.getElementById('hoverText').style.display = 'block';
    }

    function hideText() {
        document.getElementById('hoverText').style.display = 'none';
    }

    document.addEventListener('DOMContentLoaded', function() {
    const convertButton = document.getElementById('convert-button');

    if (convertButton) {
        convertButton.addEventListener('click', function() {
            const form = document.getElementById('convert-form');
            const formData = new FormData(form);

            fetch('{{ route('convert.price.list') }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(columns => {
                const columnsContainer = document.getElementById('columns-container');
                columnsContainer.innerHTML = '';

                const columnNamesDiv = document.createElement('div');
                columnNamesDiv.className = 'col-span-1';

                const h2Columns = document.createElement('h2');
                h2Columns.textContent = 'Найденные столбцы в вашем файле';
                h2Columns.className = 'text-xl font-bold mb-4'; 
                columnsContainer.appendChild(h2Columns);

                const h2Data = document.createElement('h2');
                h2Data.textContent = 'Данные которые содержит столбец';
                h2Data.className = 'text-xl font-bold mb-4'; 
                columnsContainer.appendChild(h2Data);

                const selectDiv = document.createElement('div');
                selectDiv.className = 'col-span-1';

                columns.forEach((column, index) => {
                    const labelDiv = document.createElement('div');
                    labelDiv.className = 'border border-gray-300 h-10 mb-4';

                    const label = document.createElement('label');
                    label.className = 'block text-gray-700';
                    label.textContent = column;

                    const select = document.createElement('select');
                    select.className = 'form-control border h-10 w-full';
                    select.name = column;

                    const options = {
                        'Выберите поле': 'none',
                        'Артикул': 'art_number',
                        'Название товара': 'product_name',
                        'Состояние': 'new_used',
                        'Марка': 'brand',
                        'Модель': 'model',
                        'Кузов': 'body',
                        'Номер запчасти': 'number',
                        'Номер двигателя': 'engine',
                        'Год': 'year',
                        'Расположение Л_П': 'L_R',
                        'Расположение Сп_Сз': 'F_R',
                        'Расположение Св_Сн': 'U_D',
                        'Цвет': 'color',
                        'Применимость': 'applicability',
                        'Количество': 'quantity',
                        'Цена': 'price',
                        'Доступность': 'availability',
                        'Время доставки': 'delivery_time',
                        'Главное фото': 'main_photo_url',
                        'Фото1': 'additional_photo_url_1',
                        'Фото2': 'additional_photo_url_2',
                        'Фото3': 'additional_photo_url_3'
                    };
                    for (let key in options){
                        const optionElement = document.createElement('option');
                        optionElement.textContent = key;
                        optionElement.setAttribute("value", options[key])
                        select.appendChild(optionElement);
                    }
                    
                    labelDiv.appendChild(label);
                    columnNamesDiv.appendChild(labelDiv);

                    const selectDivWrapper = document.createElement('div');
                    selectDivWrapper.className = 'mb-4';
                    selectDivWrapper.appendChild(select);

                    selectDiv.appendChild(selectDivWrapper);
                });

                columnsContainer.appendChild(columnNamesDiv);
                columnsContainer.appendChild(selectDiv);

                document.getElementById('columns-form').style.display = 'block';
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    } else {
        console.error('Element with id "convert-button" not found');
    }

    // Обработчик события submit для формы импорта
    const importColumnsForm = document.getElementById('import-columns-form');
    if (importColumnsForm) {
        importColumnsForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const html_columns = importColumnsForm.querySelectorAll('.col-span-1')
            const column_names = html_columns[0];
            const column_name_options = html_columns[1];

            let columns_dict = {}
            for (let i=0; i<column_names.childElementCount; i++){
                const name_from_file = column_names.children[i].textContent;
                const name_from_db = column_name_options.children[i].firstChild.value;
                columns_dict[name_from_db] = name_from_file;
            }

            // Получаем настройки пользователя
            fetch('{{ route('get.user.settings') }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(settings => {
                const userId = settings.user_id;
                const columns_str = JSON.stringify(columns_dict);
                const skipRows = 0;
                const encoding = "auto";
                const delimiter = ",";
                const addSheetNameToProductName = true;
                const extractDataFromProductName = true;
                const skip_empty_price_rows=true;
                const deactivate_old_ad=true;
                const split_symbols=" ";

                const formData = new FormData();
                formData.append("file", document.getElementById('file').files[0]);

                const url = new URL('http://127.0.0.1:8002/upload');
                url.searchParams.append("user_id", userId);
                url.searchParams.append("columns", columns_str);
                url.searchParams.append("skip_rows", skipRows);
                url.searchParams.append("encoding", encoding);
                url.searchParams.append("delimiter", delimiter);
                url.searchParams.append("add_sheet_name_to_product_name", addSheetNameToProductName);
                url.searchParams.append("extract_data_from_product_name", extractDataFromProductName);
                url.searchParams.append("skip_empty_price_rows", skip_empty_price_rows);
                url.searchParams.append("deactivate_old_ad", deactivate_old_ad);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                })
                .then((response) =>{
                    if (response.ok){
                        alert("Файл успешно импортирован")
                    }
                    console.log(response.status)
                    return response.text()    
                })
                .then((result) => {
                    console.log("Response", result)
                })
                .catch((error) => console.error(error));
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    } else {
        console.error('Element with id "import-columns-form" not found');
    }

});

    // Обработчик события submit для формы импорта
    const importColumnsForm = document.getElementById('import-columns-form');
    if (importColumnsForm) {
        importColumnsForm.addEventListener('submit', function(event) {
            event.preventDefault();
            
            const html_columns = importColumnsForm.querySelectorAll('.col-span-1')
            const column_names = html_columns[0];
            const column_name_options = html_columns[1];

            let columns_dict = {}
            for (let i=0; i<column_names.childElementCount; i++){
                const name_from_file = column_names.children[i].textContent;
                const name_from_db = column_name_options.children[i].firstChild.value;
                columns_dict[name_from_db] = name_from_file;
            }

            // Получаем настройки пользователя
            fetch('{{ route('get.user.settings') }}', {
                method: 'GET',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(settings => {
                const userId = settings.user_id;
                const columns_str = JSON.stringify(columns_dict);
                const skipRows = 0;
                const encoding = "auto";
                const delimiter = ",";
                const addSheetNameToProductName = true;
                const extractDataFromProductName = true;
                const skip_empty_price_rows=true;
                const deactivate_old_ad=true;
                const split_symbols=" ";

                const formData = new FormData();
                formData.append("file", document.getElementById('file').files[0]);

                const url = new URL('http://127.0.0.1:8002/upload');
                url.searchParams.append("user_id", userId);
                url.searchParams.append("columns", columns_str);
                url.searchParams.append("skip_rows", skipRows);
                url.searchParams.append("encoding", encoding);
                url.searchParams.append("delimiter", delimiter);
                url.searchParams.append("add_sheet_name_to_product_name", addSheetNameToProductName);
                url.searchParams.append("extract_data_from_product_name", extractDataFromProductName);
                url.searchParams.append("skip_empty_price_rows", skip_empty_price_rows);
                url.searchParams.append("deactivate_old_ad", deactivate_old_ad);

                fetch(url, {
                    method: 'POST',
                    body: formData,
                })
                .then((response) =>{
                    if (response.ok){
                        alert("Файл успешно импортирован")
                    }
                    console.log(response.status)
                    return response.text()    
                })
                .then((result) => {
                    console.log("Response", result)
                })
                .catch((error) => console.error(error));
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });
    } else {
        console.error('Element with id "import-columns-form" not found');
    }

});
</script>