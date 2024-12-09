<script src="https://cdn.tailwindcss.com"></script>
<script src="{{ asset('js/header.js') }}" defer></script>
<script>
    const baseUrl = '{{ url()->current() }}'; // Передаем текущий URL в JavaScript

    document.addEventListener('DOMContentLoaded', function() {
        const menuButton = document.getElementById('menu-button');
        const menu = document.getElementById('menu');
        const overlay = document.getElementById('overlay');

        menuButton.addEventListener('click', function() {
            menu.classList.toggle('hidden');
            overlay.classList.toggle('hidden');
        });

        overlay.addEventListener('click', function() {
            menu.classList.add('hidden');
            overlay.classList.add('hidden');
        });
    });
</script>

<div class="header bg-white p-5 text-center fixed top-0 left-0 w-full z-10">
    <div class="logo float-left">
        <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.index', null, request()->get('city')) }}" class="text-2xl text-blue-500 font-bold">
            @if(auth()->check() && auth()->user()->user_status == 1 && auth()->user()->logo_url)
                <img src="{{ auth()->user()->logo_url }}" alt="Логотип" class="logourl w-3/4 h-16 mt-[-3rem]">
            @else
                <span class="text-3xl">Где</span><strong class="text-3xl text-black">Запчасть</strong><span class="text-lg">.</span><strong class="text-3xl text-black">рф</strong>
            @endif
        </a>
    </div>

    <!-- Кнопка меню только для мобильных устройств -->
    <div class="float-right md:hidden">
        <button id="menu-button" class="text-black no-underline text-base">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
    </div>

    <!-- Навигация для больших экранов -->
    <div class="city-selector hidden md:flex flex-wrap justify-center items-center">
        <select id="city" name="city" onchange="updateCitySelection()" class="text-black bg-white border border-gray-300 rounded-md p-2 w-44">
            <option value="">Все города</option> <!-- Добавлено значение "Все города" -->
            <!-- Здесь будут добавлены города через JavaScript -->
        </select>
   
        @if(auth()->check())
            <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('user.show', auth()->user()->id, request()->get('city')) }}" class="text-black no-underline mx-2.5 text-base">Профиль</a>
            <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('chats.index', null, request()->get('city')) }}" class="btn btn-secondary text-black no-underline mx-2.5 text-base">Сообщения</a>

            @if(auth()->user()->user_status == 0)
                <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.viewed', null, request()->get('city')) }}" class="text-black no-underline mx-2.5 text-base">Вы посмотрели</a>
                <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.favorites', null, request()->get('city')) }}" class="text-black no-underline mx-2.5 text-base">Избранное</a>
            @else
                @if(auth()->user()->user_status != 2)
                    <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.create', null, request()->get('city')) }}" class="text-black no-underline mx-2.5 text-base">Разместить товары</a>
                    <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.my_adverts', null, request()->get('city')) }}" class="text-black no-underline mx-2.5 text-base">Мои товары</a>
                @endif
            @endif

            @if(auth()->user()->is_seller)
                <!-- Здесь можно добавить ссылки или элементы для продавца -->
            @endif

            <!-- Кнопка "Выйти" для авторизованных пользователей -->
            <form action="{{ route('logout') }}" method="POST" style="display:inline;" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-danger text-black no-underline mx-2.5 text-base">Выйти</button>
            </form>
        @else
            <!-- Кнопка "Войти" для незарегистрированных пользователей -->
            <a href="{{ route('login') }}" class="btn btn-primary text-black no-underline mx-2.5 text-base">Войти</a>
        @endif
    </div>
</div>

<!-- Затемнение фона -->
<div id="overlay" class="hidden fixed top-[70px] left-0 w-full h-full bg-black bg-opacity-50 z-20"></div>

<!-- Меню -->
<div id="menu" class="hidden fixed top-[70px] left-0 w-full h-full bg-white z-30 flex flex-col justify-center items-center">
    <ul class="space-y-4 text-center">
        
        <li><a href="{{route('about')}}" class="block text-lg text-gray-700 hover:text-gray-900">О проекте</a></li>
        <li><a href="{{route('oferta')}}" class="block text-lg text-gray-700 hover:text-gray-900">Оферта</a></li>
        <li><a href="{{route('franchise.index')}}" class="block text-lg text-gray-700 hover:text-gray-900">Франшиза</a></li>
        <li><a href="{{route('help.index')}}" class="block text-lg text-gray-700 hover:text-gray-900">Справка</a></li>
    </ul>
</div>

<!-- Навигация для мобильных устройств -->
<div class="fixed bottom-0 left-0 w-full bg-white shadow-lg p-2 z-10 flex justify-around items-center md:hidden overflow-x-hidden">
    <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.index', null, request()->get('city')) }}" class="text-black no-underline text-xs flex flex-col items-center flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
        Поиск
    </a>
    @if(auth()->check())
    <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('adverts.favorites', null, request()->get('city')) }}" class="text-black no-underline text-xs flex flex-col items-center flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Избранное
    </a>
    <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('chats.index', null, request()->get('city')) }}" class="text-black no-underline text-xs flex flex-col items-center flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        Сообщения
    </a>
   
    <a href="{{ \App\Helpers\UrlHelper::generateUrlWithCity('user.show', auth()->user()->id, request()->get('city')) }}" class="text-black no-underline text-sm flex flex-col items-center flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        Профиль
    </a>
    @else
    <a href="{{route('login')}}" class="text-black no-underline text-sm flex flex-col items-center flex-shrink-0">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        Профиль
    </a>
    @endif
</div>