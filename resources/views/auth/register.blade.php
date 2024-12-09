<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Регистрация</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://api-maps.yandex.ru/2.1/?apikey=bef13086-2418-4e93-bac9-45e709948f50&lang=ru_RU&suggest_apikey=bef13086-2418-4e93-bac9-45e709948f50" type="text/javascript"></script>  
    <script src="{{ asset('js/register.js') }}" type="text/javascript"></script>  <!-- Подключение JavaScript файла -->
</head>
<body class="font-sans bg-gray-100">
    @include('components.header-seller')   

    <div class="header text-center mt-40">
        <a href="{{ route('login') }}" class="text-blue-500 hover:underline">Есть аккаунт? Войти</a>
    </div>

    <div class="container mx-auto mt-8 p-4 bg-white rounded shadow-md max-w-md">
        <h2 class="text-2xl font-bold text-center mb-4">Регистрация</h2>

        <form id="registerForm" method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            
            <!-- Выбор роли: клиент или продавец -->
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="user_status" value="0" checked class="mr-2"> Клиент
                </label>
                <label class="flex items-center">
                    <input type="radio" name="user_status" value="1" class="mr-2"> Продавец
                </label>
            </div>
            
            <!-- Поле для имени пользователя -->
            <input type="text" name="username" id="usernameInput" placeholder="Название компании" required class="w-full p-2 border rounded-md">
            
            <!-- Поле для email -->
            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded-md">
            
            <div class="password-container relative">
                <!-- Поле для пароля -->
                <input type="password" name="password" id="passwordInput" placeholder="Пароль" required class="w-full p-2 border rounded-md">
                <span class="toggle-password absolute right-2 top-1/2 transform -translate-y-1/2 cursor-pointer" onclick="togglePasswordVisibility('passwordInput', 'confirmPasswordInput', this)">
                    <img src="images/close_password.png" alt="Показать" class="password-icon w-5 h-5">
                </span>
            </div>
            
            <!-- Поле для подтверждения пароля -->
            <input type="password" name="password_confirmation" id="confirmPasswordInput" placeholder="Повторите пароль" required class="w-full p-2 border rounded-md">
            
            <!-- Поле для телефона -->
            <input type="text" id="phoneInput" name="phone" placeholder="Телефон (7XXXXXXXXXX)" required maxlength="19" class="w-full p-2 border rounded-md">

            <!-- Поле для города с автозаполнением -->
            <input type="text" id="cityInput" name="city" placeholder="Введите город" required autocomplete="off" class="w-full p-2 border rounded-md">
            <div id="citySuggestions" class="suggest-view max-h-52 overflow-y-auto bg-white border rounded-md hidden"></div>

            <!-- Поле для адреса с автозаполнением -->
            <input type="text" id="addressInput" name="address_line" placeholder="Введите адрес" required class="w-full p-2 border rounded-md">

            <!-- Чекбокс для согласия с офертой -->
            <a href="" class="text-blue-500 hover:underline">Забыли пароль?</a>
            <div class="flex items-center">
                <input type="checkbox" id="agree" name="agree" required class="mr-2">
                <label for="agree" class="text-sm">
                    Я прочитал и согласен с <a href="{{ url('/oferta') }}" target="_blank" class="text-blue-500 hover:underline">офертой</a>
                </label>
            </div>
            <div class="flex items-center">
    <input type="checkbox" id="agree_pd" name="agree_pd" required class="mr-2">
    <label for="agree_pd" class="text-sm">
        Я прочитал и согласен с <a href="{{ url('/privacy-policy') }}" target="_blank" class="text-blue-500 hover:underline">обработкой персональных данных</a>
    </label>
</div>
            <p id="agreementError" class="text-red-500 hidden">Подтвердите, что ознакомлены и согласны с офертой.</p>

            <!-- Обработка ошибок валидации -->
            @if ($errors->any())
                <div class="error text-red-500">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <button type="submit" class="w-full p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Зарегистрироваться</button>
        </form>

        <p class="mt-4 text-center">После регистрации вам будет отправлено письмо для подтверждения почты.</p>
    </div>

    <script>
        // Функция для изменения placeholder в зависимости от выбранной роли
        function updatePlaceholder() {
            const usernameInput = document.getElementById('usernameInput');
            const clientRadio = document.querySelector('input[name="user_status"][value="0"]');
            const sellerRadio = document.querySelector('input[name="user_status"][value="1"]');

            if (clientRadio.checked) {
                usernameInput.placeholder = 'Ваше имя';
            } else if (sellerRadio.checked) {
                usernameInput.placeholder = 'Название компании';
            }
        }

        // Добавляем обработчик события change на радиокнопки
        document.querySelectorAll('input[name="user_status"]').forEach(radio => {
            radio.addEventListener('change', updatePlaceholder);
        });

        // Инициализация placeholder при загрузке страницы
        updatePlaceholder();
    </script>
</body>
</html>