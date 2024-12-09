<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Авторизация</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="font-sans bg-gray-100 mt-40">
    @include('components.header-seller')   

    <div class="header text-center mt-4">
        <a href="{{ route('register') }}" class="text-blue-500 hover:underline">Нет аккаунта? Зарегистрируйся</a>
    </div>

    <div class="container mx-auto mt-8 p-4 bg-white rounded shadow-md max-w-md">
        <h2 class="text-2xl font-bold text-center mb-4">Авторизация</h2>

        @if ($errors->any())
            <div class="text-red-500 mb-4">
                <strong>{{ $errors->first() }}</strong>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <input type="email" name="email" placeholder="Email" required class="w-full p-2 border rounded-md">
            <input type="password" name="password" placeholder="Пароль" required class="w-full p-2 border rounded-md">
            <div class="flex items-center">
                <input type="checkbox" name="remember" id="remember" class="mr-2">
                <label for="remember" class="text-sm">Запомнить меня</label>
            </div>
            <button type="submit" class="w-full p-2 bg-blue-500 text-white rounded-md hover:bg-blue-600">Войти</button>
        </form>
    </div>
</body>
</html>