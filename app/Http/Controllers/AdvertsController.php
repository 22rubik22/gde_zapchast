<?php

namespace App\Http\Controllers;

use App\Models\Advert;
use App\Models\UserQuery;
use Illuminate\Http\Request;
use App\Models\BaseAvto;
use App\Models\Part;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cookie;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class AdvertsController extends Controller
{
    // Показать все объявления со статусом "activ"
    public function index(Request $request)
    {
        // Получаем объявления со статусом "activ"
        $query = Advert::where('status_ad', 'activ');
    
        // Фильтрация по городу, если параметр передан
        if ($request->has('city') && $request->input('city') !== '') {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('city', $request->input('city'));
            });
        }
    
        // Пагинация объявлений
        $adverts = $query->paginate(20);
    
        // Получаем список городов для выпадающего списка
        $cities = User::distinct()->pluck('city')->toArray(); // Получаем уникальные города из модели User
    
        return view('adverts.index', compact('adverts', 'cities'));
    }

    // Показать форму для создания нового объявления
    public function create()
    {
        return view('adverts.create');
    }

    public function store(Request $request)
    {
        // Валидация данных
        $validatedData = $request->validate([
            'art_number' => 'required',
            'product_name' => 'required',
            'brand' => 'required',
            'price' => 'required|numeric|min:0',
        ]);
    
        // Создание объявления
        $advert = new Advert();
        $advert->user_id = auth()->id(); // Предполагается, что пользователь авторизован
        $advert->art_number = $validatedData['art_number'];
        $advert->product_name = $validatedData['product_name'];
        $advert->brand = $validatedData['brand'];
        $advert->price = $validatedData['price'];
  
        // Присвоение необязательных полей, если они присутствуют в запросе
        $optionalFields = [
            'number','model', 'new_used', 'year', 'body', 'engine', 'L_R', 'F_R', 'U_D', 
            'color', 'applicability', 'quantity', 'availability', 'main_photo_url', 
            'additional_photo_url_1', 'additional_photo_url_2', 'additional_photo_url_3'
        ];

        foreach ($optionalFields as $field) {
            if ($request->has($field)) {
                $advert->$field = $request->input($field);
            }
        }
        $advert->save();
    
        return redirect()->route('adverts.index')->with('success', 'Объявление успешно создано.');
    }

    // страница объявления
    public function show($id)
    {
        $advert = Advert::findOrFail($id);
         // Получаем текущее значение массива из куки
         $currentArray = json_decode(request()->cookie('viewed', '[]'), true);

         // Добавляем новый элемент в массив
         $currentArray[$id] = 1;
     
         // Сохраняем обновленный массив в куки
         Cookie::queue('viewed', json_encode($currentArray), 9999);
     
        // Найти детали, которые соответствуют product_name
        $parts = $this->findPartsByProductName($advert->product_name);

        // Инициализируем переменные для хранения найденной детали
        $foundPartId = null;
        $foundPartName = null;
        $foundQueryId = null;

        // Если детали найдены, берем первую
        if ($parts->isNotEmpty()) {
            $foundPartId = $parts->first()->part_id;
            $foundPartName = $parts->first()->part_name;

            // Получаем id_queri из первого найденного запроса
            $firstQuery = UserQuery::where('id_part', $foundPartId)->first();
            if ($firstQuery) {
                $foundQueryId = $firstQuery->id_queri;
            }
        }

        // Поиск id_modification в модели BaseAvto
        $modificationId = $this->findModificationId($advert);

        // Поиск всех запросов с найденным id_part и id_car
        $userQueries = UserQuery::where('id_part', $foundPartId)
            ->where('id_car', $modificationId)
            ->get();

        // Извлекаем все id_queri из userQueries
        $queryIds = $userQueries->pluck('id_queri')->toArray();

        // Поиск всех запросов с найденными id_queri
        $relatedQueries = UserQuery::whereIn('id_queri', $queryIds)->get();

        // Получаем данные из BaseAvto для каждого связанного запроса
        $relatedCars = $this->getRelatedCars($relatedQueries);

        $userAddress = $advert->user->userAddress->address_line;
        $product_name = $advert->product_name;
        $main_photo_url = $advert->main_photo_url;
        $address_line = $userAddress;

        // Передать товар, найденную деталь, модификацию и запросы в представление
        return view('adverts.show', compact('advert', 'foundPartId', 'foundPartName', 'modificationId', 'userQueries', 'relatedQueries', 'relatedCars', 
        'userAddress', 'product_name',  'main_photo_url',  'address_line', ));
    }

    private function findPartsByProductName($productName)
    {
        return Part::where(Part::raw("'{$productName}'"), 'LIKE', Part::raw("CONCAT('%', part_name, '%')"))->get();
    }

    private function findModificationId($advert)
    {
        $baseAvto = BaseAvto::where('brand', $advert->brand)
            ->where('model', $advert->model)
            ->where('year_from', '<=', $advert->year)
            ->where('year_before', '>=', $advert->year)
            ->first();

        return $baseAvto ? $baseAvto->id_modification : null;
    }

    private function getRelatedCars($relatedQueries)
    {
        $relatedCars = [];
        
        foreach ($relatedQueries as $relatedQuery) {
            // Используем find для получения данных по id_modification
            $carData = BaseAvto::find($relatedQuery->id_car);
            
            if ($carData) {
                $relatedCars[] = [
                    'brand' => $carData->brand,
                    'model' => $carData->model,
                    'generation' => $carData->generation,
                    'year_from' => $carData->year_from,
                    'year_before' => $carData->year_before,
                    'modification' => $carData->modification,
                ];
            }
        }

        return $relatedCars;
    }
    
    // Обновить данные объявления в базе данных
    public function update(Request $request)
    {
        $advert = Advert::find($request->id);
    
        // Обновление текстовых полей
        if ($request->art_number !== $request->old_art_number) {
            $advert->art_number = $request->art_number;
        }
        if ($request->product_name !== $request->old_product_name) {
            $advert->product_name = $request->product_name;
        }
        if ($request->number !== $request->old_number) {
            $advert->number = $request->number;
        }
        if ($request->new_used !== $request->old_new_used) {
            $advert->new_used = $request->new_used;
        }
        if ($request->brand !== $request->old_brand) {
            $advert->brand = $request->brand;
        }
        if ($request->model !== $request->old_model) {
            $advert->model = $request->model;
        }
        if ($request->year !== $request->old_year) {
            $advert->year = $request->year;
        }
        if ($request->body !== $request->old_body) {
            $advert->body = $request->body;
        }
        if ($request->engine !== $request->old_engine) {
            $advert->engine = $request->engine;
        }
        if ($request->L_R !== $request->old_L_R) {
            $advert->L_R = $request->L_R;
        }
        if ($request->F_R !== $request->old_F_R) {
            $advert->F_R = $request->F_R;
        }
        if ($request->U_D !== $request->old_U_D) {
            $advert->U_D = $request->U_D;
        }
        if ($request->color !== $request->old_color) {
            $advert->color = $request->color;
        }
        if ($request->applicability !== $request->old_applicability) {
            $advert->applicability = $request->applicability;
        }
        if ($request->quantity !== $request->old_quantity) {
            $advert->quantity = $request->quantity;
        }
        if ($request->price !== $request->old_price) {
            $advert->price = $request->price;
        }
        if ($request->availability !== $request->old_availability) {
            $advert->availability = $request->availability;
        }
    
        // Обновление URL фотографий
        if ($request->main_photo_url !== $request->old_main_photo_url) {
            $advert->main_photo_url = $request->main_photo_url;
        }
        if ($request->additional_photo_url_1 !== $request->old_additional_photo_url_1) {
            $advert->additional_photo_url_1 = $request->additional_photo_url_1;
        }
        if ($request->additional_photo_url_2 !== $request->old_additional_photo_url_2) {
            $advert->additional_photo_url_2 = $request->additional_photo_url_2;
        }
        if ($request->additional_photo_url_3 !== $request->old_additional_photo_url_3) {
            $advert->additional_photo_url_3 = $request->additional_photo_url_3;
        }
    
        $advert->save();
    
        return redirect()->route('adverts.my_adverts')->with('success', 'Объявление успешно обновлено');
    }

    // получить все активные объявления текущего пользователя
    public function myAdverts(Request $request)
    {
        $userId = auth()->id(); // Получаем ID текущего пользователя

        // Получаем все активные объявления текущего пользователя
        $query = Advert::where('user_id', $userId)
                       ->where('status_ad', 'activ');

        // Поиск по product_name и number
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('product_name', 'like', "%{$search}%")
                  ->orWhere('number', 'like', "%{$search}%");
            });
        }

        // Получение списка марок для выпадающего списка, только для объявлений текущего пользователя
        $brands = Advert::where('user_id', $userId)
                        ->where('status_ad', 'activ')
                        ->select('brand')
                        ->distinct()
                        ->pluck('brand');

        // Фильтрация по выбранной марке
        if ($request->filled('brand')) {
            $brand = strtolower($request->input('brand')); // Приводим к нижнему регистру
            $query->whereRaw('LOWER(brand) = ?', [$brand]); // Фильтруем по марке
        }

        // Получение отфильтрованных объявлений
        $adverts = $query->paginate(100);

        return view('adverts.my_adverts', compact('adverts', 'brands'));
    }

    // Удалить объявление из базы данных
    public function destroy($id)
    {
        $advert = Advert::findOrFail($id);
        $advert->delete();
        return redirect()->route('adverts.index')->with('success', 'Объявление удалено успешно.');
    }
    
    public function viewed(Request $request)
    {
        // Получаем данные из куки и преобразуем в массив
        $testData = json_decode($request->cookie('viewed', '[]'), true);

        foreach ($testData as $id => $value) {
            $advert = Advert::find($id);
            if ($advert) {
                $adverts[] = $advert;
            }
        }

        // Передаем данные в представление
        return view('adverts.viewed', compact('adverts'));
    }

    public function viewAdvert(Request $request, $advertId)
    {
        // Получаем данные из куки
        $viewedAdverts = json_decode($request->cookie('viewed_adverts', '[]'), true);

        // Добавляем новый товар в список просмотренных
        if (!in_array($advertId, $viewedAdverts)) {
            $viewedAdverts[] = $advertId;
        }

        // Логируем данные перед сохранением в куки
        Log::info('Сохраняем в куки: ' . json_encode($viewedAdverts));

        // Сохраняем обновленный список в куки
        $cookie = Cookie::make('viewed_adverts', json_encode($viewedAdverts), 60 * 24 * 7); // 1 неделя

        // Проверяем, что куки создается корректно
        if ($cookie) {
            Log::info('Куки создана: ' . $cookie->getValue());
        } else {
            Log::error('Ошибка при создании куки');
        }

        $value = Cookie::get('test-cookie-2');

        echo $value;

        //return redirect()->back()->withCookie($cookie);
        return $value;
    }

    public function favorites(Request $request)
    {
        // Логика для отображения просмотренных товаров
        return view('adverts.favorites');
    }

    public function search(Request $request)
{
    $query = Advert::query()->where('status_ad', 'activ');

    // Проверяем наличие $searchQuery
    $searchQuery = $request->input('search_query');

    // Проверяем наличие марка, модель и год
    $brand = $request->input('brand');
    $model = $request->input('model');
    $year = $request->input('year');
    
     // Если указана только марка, ищем только по марке
     if ($request->filled('brand') && !$request->filled('model') && !$request->filled('search_query')) {
        $query->where('brand', $brand);
    }

    // Если $searchQuery задан, добавляем условия поиска по названию или номеру детали
    if ($request->filled('search_query')) {
        $words = explode(' ', $searchQuery);

        $query->where(function($q) use ($words) {
            foreach ($words as $word) {
                $q->orWhere('product_name', 'like', '%' . $word . '%');
            }
        });

        // Определяем part_id запчасти
        $part = Part::where(function($q) use ($words) {
            foreach ($words as $word) {
                $q->orWhere('part_name', 'like', '%' . $word . '%');
            }
        })->first();

        if ($part) {
            $partId = $part->part_id; // Сохраняем найденный part_id

            Log::info('Запчасть: ' . $part->part_name);
            $Part_search = $part->part_name;

            // Получаем модификации из сессии или куки
            $id_modification = $request->input('id_modification', []); // Заменить на получение из куки

            // Проверяем, есть ли модификации
            if (!empty($id_modification)) {
                Log::info('Найдены id модификации:', $id_modification);

                // Получаем id_queri по id_part и id_car
                $idQuery = UserQuery::where('id_part', $partId)
                    ->whereIn('id_car', $id_modification)
                    ->pluck('id_queri')
                    ->first();

                Log::info('id запроса: ' . json_encode($idQuery));

                if (!empty($idQuery)) {
                    // Получаем все модификации по найденному id_queri
                    $modifications = UserQuery::where('id_queri', $idQuery)
                        ->pluck('id_car');

                    Log::info('Найдены id модификаций: ' . json_encode($modifications));

                    // Получаем уникальные автомобили с их годами
                    $matchingCars = BaseAvto::whereIn('id_modification', $modifications)
                        ->get(['brand', 'model', 'year_from', 'year_before'])
                        ->unique(function ($item) {
                            return $item['brand'] . $item['model'] . $item['year_from'] . $item['year_before'];
                        })
                        ->values();

                    Log::info('Уникальные автомобили: ' . json_encode($matchingCars));

                    // Получаем все названия запчастей
                    $partNames = Part::pluck('part_name')->toArray();

                    // Формируем массив уникальных условий для запросов
                    $uniqueConditions = [];

                    foreach ($matchingCars as $car) {
                        $key = strtolower($car->brand) . '|' . strtolower($car->model) . '|' . $car->year_from . '|' . $car->year_before;
                        if (!isset($uniqueConditions[$key])) {
                            $uniqueConditions[$key] = [
                                'brand' => strtolower($car->brand),
                                'model' => strtolower($car->model),
                                'year_from' => $car->year_from,
                                'year_before' => $car->year_before,
                            ];
                        }
                    }

                    Log::info('Уникальные автомобили (поиск): ' . json_encode(array_values($uniqueConditions)));

                    // Инициализируем пустой массив для хранения всех найденных объявлений
                    $allAds = [];

                    // Создаем массив для хранения условий
                    $conditions = [];

                    // Формируем массив условий для запроса
                    foreach ($uniqueConditions as $condition) {
                        $conditions[] = [
                            'brand' => $condition['brand'],
                            'model' => $condition['model'],
                            'year_from' => $condition['year_from'],
                            'year_before' => $condition['year_before'],
                        ];
                    }

                    // Выполняем общий запрос
                    $ads = Advert::where(function($query) use ($conditions, $Part_search) {
                        foreach ($conditions as $condition) {
                            $query->orWhere(function($subQuery) use ($condition, $Part_search) {
                                $subQuery->where('brand', $condition['brand'])
                                    ->where('model', $condition['model'])
                                    ->where('year', '>=', $condition['year_from'])
                                    ->where('year', '<=', $condition['year_before'])
                                    ->where(function($q) use ($Part_search) {
                                        $words = explode(' ', $Part_search);
                                        foreach ($words as $word) {
                                            $q->orWhere('product_name', 'like', '%' . $word . '%');
                                        }
                                    })
                                    ->where('status_ad', 'activ');
                            });
                        }
                    })->get();

                    // Преобразуем результат в массив
                    $allAds = $ads->toArray();

                    // Логируем найденные объявления
                    Log::info('Найдены объявления с запчастью ' . $Part_search . ': ' . json_encode($allAds));

                    // Преобразуем массив обратно в коллекцию для удобства работы с пагинацией
                    $adverts = collect($allAds);

                    // Оставляем только уникальные записи по id
                    $uniqueAdverts = $adverts->unique('id');

                    // Получаем массив уникальных id
                    $uniqueIds = $uniqueAdverts->pluck('id')->toArray();

                    // Получаем уникальные значения engine из уже найденных объявлений
                    $engines = $uniqueAdverts->pluck('engine')->unique()->values()->toArray();

                    // Теперь используем массив уникальных id для выборки из модели Advert
                    $query = Advert::whereIn('id', $uniqueIds);

                    // Фильтрация по параметру engine, если он был передан
                    if ($request->filled('engines')) {
                        $selectedEngines = $request->input('engines');
                        
                        // Проверяем, что выбранные engines находятся в списке доступных
                        $validEngines = array_intersect($selectedEngines, $engines);
                        
                        if (!empty($validEngines)) {
                            $query->whereIn('engine', $validEngines);
                        }
                    }

                    // Получение результатов с пагинацией
                    $adverts = $query->paginate(20);

                    $addresses = $adverts->map(function ($advert) {
                        return $advert->product_name ?? 'Не указан';
                    })->filter()->values()->toArray();
                
                    $prod_name = $adverts->map(function ($advert) {
                        return $advert->product_name ?? 'Не указан';
                    })->filter()->values()->toArray();

                    $image_prod = $adverts->map(function ($advert) {
                        return $advert->main_photo_url ?? '';
                    })->filter()->values()->toArray();

                    $advert_ids = $adverts->map(function ($advert) {
                        return $advert->id;
                    })->filter()->values()->toArray();
                
                    // Возврат результатов в представление
                    return view('adverts.search', compact('adverts', 'engines', 'addresses', 'prod_name', 'image_prod', 'advert_ids'));
                } else {
                    // Одиночный запрос
                    // Фильтрация по марке и модели
                    if ($request->filled('brand') && $request->filled('model')) {
                        if ($request->filled('year')) {
                            // Получаем поколение модели по году
                            $generation = BaseAvto::where('brand', $brand)
                                ->where('model', $model)
                                ->where('year_from', '<=', $year)
                                ->where('year_before', '>=', $year)
                                ->first();

                            if ($generation) {
                                // Если поколение найдено, фильтруем по годам
                                $query->whereBetween('year', [$generation->year_from, $generation->year_before]);
                            } else {
                                return back()->withErrors(['message' => 'Для указанного года не найдено подходящего поколения модели.']);
                            }
                        }

                        // Фильтрация по марке и модели
                        $query->where('brand', $brand)->where('model', $model);
                    } elseif ($request->filled('brand')) {
                        // Поиск объявлений только по марке
                        $query->where(function($q) use ($brand) {
                            $q->where('brand', 'like', '%' . $brand . '%')
                              ->orWhere('applicability', 'like', '%' . $brand . '%');
                        });
                    }
                }
            } else {
                // Если модификации не выбраны, продолжаем поиск без учета модификаций
                // Фильтрация по марке и модели
                if ($request->filled('brand') && $request->filled('model')) {
                    if ($request->filled('year')) {
                        // Получаем поколение модели по году
                        $generation = BaseAvto::where('brand', $brand)
                            ->where('model', $model)
                            ->where('year_from', '<=', $year)
                            ->where('year_before', '>=', $year)
                            ->first();

                        if ($generation) {
                            // Если поколение найдено, фильтруем по годам
                            $query->whereBetween('year', [$generation->year_from, $generation->year_before]);
                        } else {
                            return back()->withErrors(['message' => 'Для указанного года не найдено подходящего поколения модели.']);
                        }
                    }

                    // Фильтрация по марке и модели
                    $query->where('brand', $brand)->where('model', $model);
                } elseif ($request->filled('brand')) {
                    // Поиск объявлений только по марке
                    $query->where(function($q) use ($brand) {
                        $q->where('brand', 'like', '%' . $brand . '%')
                          ->orWhere('applicability', 'like', '%' . $brand . '%');
                    });
                }
            }
        } else {
            // Если название детали не найдено, проверяем на наличие номера детали в таблице users_queries
            $userQueries = UserQuery::where('id_queri', $searchQuery)->get();

            if ($userQueries->isNotEmpty()) {
                // Если номер детали найден, выводим сообщение в консоль
                Log::info('Происходит поиск по номеру');

                // Получаем все id_queri и id_car
                $idQueriList = $userQueries->pluck('id_queri')->toArray();
                $idCarList = $userQueries->pluck('id_car')->toArray();
                
                // Выводим id_queri и id_car в консоль
                Log::info('Найдены id_queri: ' . json_encode($idQueriList));
                Log::info('Найдены id_car: ' . json_encode($idCarList));
                
                // Получаем id_modification по id_car из таблицы base_avto
                $modifications = BaseAvto::whereIn('id_modification', $idCarList)->get();
                
                // Инициализируем пустой массив для хранения всех найденных объявлений
                $allAds = [];
                
                // Выводим brand, model, year_from, year_before для каждого id_modification в консоль
                foreach ($modifications as $modification) {
                    Log::info('id_modification: ' . $modification->id_modification);
                    Log::info('brand: ' . $modification->brand);
                    Log::info('model: ' . $modification->model);
                    Log::info('year_from: ' . $modification->year_from);
                    Log::info('year_before: ' . $modification->year_before);
                
                    // Ищем объявления в таблице adverts
                    $ads = Advert::where('brand', $modification->brand)
                        ->where('model', $modification->model)
                        ->where('year', '>=', $modification->year_from)
                        ->where('year', '<=', $modification->year_before)
                        ->where('status_ad', 'activ')
                        ->get();
                        $allAds = array_merge($allAds, $ads->toArray());

                        $ads2 = Advert::where('number', $searchQuery)->get();
                // Проверяем, что объявление найдено
                if ($ads2->isNotEmpty()) {
                    // Добавляем найденное объявление в общий массив
                    $allAds = array_merge($allAds, $ads2->toArray());
                } else {
                    Log::info('Объявление с number = ' . $searchQuery . ' не найдено.');
                }
                    // Выводим найденные объявления в консоль
                    Log::info('Найдены объявления для автомобиля: ' . $modification->brand . ' ' . $modification->model . ' ' . $modification->year_from . '-' . $modification->year_before);
                    Log::info(json_encode($ads));
                }
                
                // Считаем количество найденных объявлений
                $totalAdsCount = count($allAds);
                Log::info('Общее количество найденных объявлений: ' . $totalAdsCount);
                
                // Преобразуем массив обратно в коллекцию для удобства работы с пагинацией
                $adverts = collect($allAds);
                
                // Оставляем только уникальные записи по id
                $uniqueAdverts = $adverts->unique('id');
                
                // Получаем массив уникальных id
                $uniqueIds = $uniqueAdverts->pluck('id')->toArray();
                
                // Получаем уникальные значения engine из уже найденных объявлений
                $engines = $uniqueAdverts->pluck('engine')->unique()->values()->toArray();
                
                // Теперь используем массив уникальных id для выборки из модели Advert
                $query = Advert::whereIn('id', $uniqueIds);
                
                // Фильтрация по параметру engine, если он был передан
                if ($request->filled('engines')) {
                    $selectedEngines = $request->input('engines');
                    
                    // Проверяем, что выбранные engines находятся в списке доступных
                    $validEngines = array_intersect($selectedEngines, $engines);
                    
                    if (!empty($validEngines)) {
                        $query->whereIn('engine', $validEngines);
                    }
                }
                
                // Получение результатов с пагинацией
                $adverts = $query->paginate(20);
                
                $addresses = $adverts->map(function ($advert) {
                    return $advert->product_name ?? 'Не указан';
                })->filter()->values()->toArray();
                
                $prod_name = $adverts->map(function ($advert) {
                    return $advert->product_name ?? 'Не указан';
                })->filter()->values()->toArray();
                
                $image_prod = $adverts->map(function ($advert) {
                    return $advert->main_photo_url ?? '';
                })->filter()->values()->toArray();
                
                $advert_ids = $adverts->map(function ($advert) {
                    return $advert->id;
                })->filter()->values()->toArray();
                
                // Возврат результатов в представление
                return view('adverts.search', compact('adverts', 'engines', 'addresses', 'prod_name', 'image_prod', 'advert_ids'));
            }
        }
    }

    // Фильтрация по параметру engine
    if ($request->filled('engines')) {
        $selectedEngines = $request->input('engines');
        $query->whereIn('engine', $selectedEngines); // Предполагается, что 'engine' — это поле в вашей таблице
    }

    // Получение всех уникальных значений для engine из найденных объявлений
    $engines = Advert::query()
        ->where('status_ad', 'activ')
        ->when($searchQuery, function($query) use ($searchQuery) {
            return $query->where(function($q) use ($searchQuery) {
                $words = explode(' ', $searchQuery);
                foreach ($words as $word) {
                    $q->orWhere('product_name', 'like', '%' . $word . '%');
                }
            });
        })
        ->when($brand, function($query) use ($brand) {
            return $query->where('brand', 'like', '%' . $brand . '%');
        })
        ->when($model, function($query) use ($model) {
            return $query->where('model', $model);
        })
        ->when($year, function($query) use ($year, $brand, $model) {
            // Получаем поколение модели по году
            $generation = BaseAvto::where('brand', $brand)
                ->where('model', $model)
                ->where('year_from', '<=', $year)
                ->where('year_before', '>=', $year)
                ->first();

            if ($generation) {
                return $query->whereBetween('year', [$generation->year_from, $generation->year_before]);
            }
            return $query;
        })
        ->distinct()
        ->pluck('engine');

    // Получение результатов с пагинацией
    $adverts = $query->paginate(20);

    // Формируем массив адресов
    $addresses = $adverts->map(function ($advert) {
        return $advert->user->userAddress->address_line ?? 'Не указан';
    })->filter()->values()->toArray();

    $prod_name = $adverts->map(function ($advert) {
        return $advert->product_name ?? 'Не указан';
    })->filter()->values()->toArray();

    $image_prod = $adverts->map(function ($advert) {
        return $advert->main_photo_url ?? '';
    })->filter()->values()->toArray();

    $advert_ids = $adverts->map(function ($advert) {
        return $advert->id;
    })->filter()->values()->toArray();

    // Возврат результатов в представление
    return view('adverts.search', compact('adverts', 'engines', 'addresses', 'prod_name', 'image_prod', 'advert_ids'));
}
}