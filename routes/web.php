<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdvertsController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConverterSetController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\CarListsController;
use App\Http\Controllers\MessageController;
use App\Models\Part;
use Illuminate\Http\Request;
use App\Http\Controllers\CookiePolicyController;
use App\Http\Controllers\OfertaController;
use App\Http\Controllers\AboutController;
use App\Http\Controllers\FranchiseController;
use App\Http\Controllers\HelpController;
use App\Http\Controllers\MarketAnalysisController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PayController;
use App\Http\Controllers\CarImportController;


// Главная страница
Route::get('/', [AdvertsController::class, 'index'])->name('home'); // Главная страница


// Ресурсный маршрут для контроллера пользователей
Route::apiResource('users', UserController::class);




// Разлогинивание
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// Ресурсный маршрут для получения города
Route::get('/cities', [UserController::class, 'getCities']);

// Оферта
Route::get('/oferta', [OfertaController::class, 'index']);
Route::get('/oferta', [OfertaController::class, 'index'])->name('oferta');

// О проекте
Route::get('/about', [AboutController::class, 'index'])->name('about');


// Франшиза
Route::get('/franchise', [FranchiseController::class, 'index'])->name('franchise.index');

// Ресурсный маршрут для контроллера объявлений
Route::resource('adverts', AdvertsController::class);

// Ресурсный маршрут для контроллера объявлений пользователей в ЛК
Route::middleware(['auth'])->group(function () {
Route::get('/my-adverts', [AdvertsController::class, 'myAdverts'])->name('adverts.my_adverts');
});


// Для списка чатов
Route::get('/chats', [ChatController::class, 'index'])->name('chats.index');

// Отображение конкретного чата
Route::get('/chat/{chat}', [ChatController::class, 'show'])->name('chat.show');

// Отправка сообщения
Route::post('/chat/{chat}/send', [ChatController::class, 'sendMessage'])->middleware('auth')->name('chat.send');

// Открытие нового чата
Route::post('/chat/open/{advert}', [ChatController::class, 'openChat'])->middleware('auth')->name('chat.open');

Route::get('/advert/{advert}', [AdvertsController::class, 'show'])->name('advert.show');
// Получение сообщений
Route::get('/chat/{chat}/messages', [ChatController::class, 'getMessages'])->name('chat.messages');

//статус сообщения
Route::post('/chat/{chat}/mark-as-read', [ChatController::class, 'markAsRead'])->name('chat.markAsRead');

// Ресурсный маршрут для настройки конвертера
Route::middleware(['auth'])->group(function () {
    Route::get('/converter-set/edit', [ConverterSetController::class, 'edit'])->name('converter_set.edit');
    Route::put('/converter-set/update', [ConverterSetController::class, 'update'])->name('converter_set.update');
});

// Ресурсный маршрут для настройки регистрации и авторизации
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::middleware(['auth'])->group(function () {
    Route::get('/user/{id}', [UserController::class, 'show'])->name('user.show');
});

// Редактирование профиля
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/{id}/edit', [UserController::class, 'edit'])->name('profile.edit');
    Route::put('/profile/{id}', [UserController::class, 'update'])->name('profile.update');
});


// Ресурсный маршрут для поиска города
Route::get('/cities/search', [CityController::class, 'search']);


// Ресурсный маршрут для страницы со всеми объявлениями (главная)
Route::get('/adverts', [AdvertsController::class, 'index'])->name('adverts.index');


// Ресурсный маршрут для страницы товара
Route::get('/adverts/{id}', [AdvertsController::class, 'show'])->name('adverts.show');

// Ресурсный маршрут для страницы результатов поиска товара
Route::get('/search', [AdvertsController::class, 'search'])->name('adverts.search');


// Форма поиска
// Ресурсный маршрут для динамических списков формы 
Route::get('/get-models', [CarListsController::class, 'getModels'])->name('get.models');
//год
Route::get('/get-years', [CarListsController::class, 'getYears']);
//модификации
Route::get('/get-modifications', [CarListsController::class, 'getModifications'])->name('get.modifications');
//id_ модификации

//Подсказки для поля ввода названия запчасти
Route::get('/parts/search', function (Request $request) {
    $query = $request->get('query');
    $parts = Part::where('part_name', 'LIKE', "%{$query}%")->pluck('part_name');
    return response()->json($parts);
});

// Стрианица помощи
Route::get('/help', [HelpController::class, 'index'])->name('help.index');

// Уведомление cookie
Route::get('/cookie-policy', function () {
    return view('cookie-policy');
})->name('cookie.policy');

//Подсказки по маркам

Route::get('/get-brands', [CarListsController::class, 'getBrands'])->name('get.brands');



Route::get('/viewed', [AdvertsController::class, 'viewed'])->name('adverts.viewed');

Route::get('/favorites', [AdvertsController::class, 'favorites'])->name('adverts.favorites');

Route::get('/adverts/{advert}', [AdvertsController::class, 'show'])->name('adverts.show');

//подтверждение почты



Route::put('/adverts/update', [AdvertController::class, 'update'])->name('adverts.update');


Route::get('/search/by-part-number', [AdvertController::class, 'searchByPartNumber'])->name('search.by.part.number');
Route::get('/search/by-part-name', [AdvertController::class, 'searchByPartName'])->name('search.by.part.name');

Route::get('/market-analysis', function () {
    return view('market');
})->name('market.analysis');

Route::get('/market-analysis', [MarketAnalysisController::class, 'index'])->name('market.analysis');

Route::get('/tariff-settings', function () {
    return view('tariff-settings');
})->name('tariff.settings');

Route::post('/adverts/import', [AdvertsController::class, 'import'])->name('adverts.import');

//Оформление заказа

Route::get('/pay', [PayController::class, 'pay'])->name('pay');

Route::get('/pay2', [PayController::class, 'pay2'])->name('pay2');

//Импорт товаров

Route::post('/admin/cars/import', [CarImportController::class, 'import'])->name('cars.import');
    Route::view("/admin/cars/import", 'cars.import')->name('cars.import.form');

    //Импорт с конвертацией

    Route::post('/convert-price-list', [ConverterSetController::class, 'convertPriceList'])->name('convert.price.list');
    Route::get('/get-user-settings', [ConverterSetController::class, 'getUserSettings'])->name('get.user.settings');
