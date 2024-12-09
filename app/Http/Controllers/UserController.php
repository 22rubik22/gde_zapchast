<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Отображение списка пользователей
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

// Отображение конкретного пользователя
public function show($id)
{
    // Получаем текущего авторизованного пользователя
    $currentUser = auth()->user();

    // Проверяем, совпадает ли ID запрашиваемого пользователя с ID текущего пользователя
    if (!$currentUser || $currentUser->id !== (int)$id) {
        return redirect()->route('login'); // Перенаправление на страницу регистрации
    }

    // Получаем информацию о пользователе
    $user = User::find($id);
    
    if (!$user) {
        return response()->json(['message' => 'Пользователь не найден'], 404);
    }

 // Получаем информацию о пользователе и его адресах
    $user = User::with('userAddress')->find($id);
    
    if (!$user) {
        return response()->json(['message' => 'Пользователь не найден'], 404);
    }

    return view('profile', ['user' => $user]);
}



    // Создание нового пользователя
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'city' => 'nullable|string|max:255',
            // Добавьте другие валидации по необходимости
        ]);

        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'city' => $request->city,
            // Добавьте другие поля по необходимости
        ]);

        return response()->json($user, 201);
    }

    // Обновление существующего пользователя
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
    
        $request->validate([
            'username' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8',
            'avatar_url' => 'nullable|url',
            'logo_url' => 'nullable|url', // Добавляем валидацию для поля logo_url
        ]);
    
        $user->update(array_filter([
            'username' => $request->username,
            'email' => $request->email,
            'password' => isset($request->password) ? Hash::make($request->password) : $user->password,
            'city' => $request->city,
            'avatar_url' => $request->input('avatar_url'),
            'logo_url' => $request->input('logo_url'), // Обновляем поле logo_url
        ]));
    
        // Перенаправление на страницу пользователя
        return redirect()->route('user.show', ['id' => $user->id])->with('success', 'Профиль успешно обновлён!');
    }
    // Удаление пользователя
    public function destroy($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();
        return response()->json(['message' => 'User deleted successfully']);
    }
    
    // Отображение формы редактирования профиля
    public function edit($id)
    {
        $user = User::find($id);
        
        if (!$user) {
            return response()->json(['message' => 'Пользователь не найден'], 404);
        }

        return view('profile_edit', ['user' => $user]);
    }

    // получеение городов для хэдера
    public function getCities()
    {
        $cities = User::select('city')
            ->distinct()
            ->whereNotNull('city') // Исключаем пустые значения
            ->where('city', '!=', '') // Исключаем пустые строки
            ->orderBy('city') // Сортировка по алфавиту
            ->pluck('city'); // Получение списка городов
    
        return response()->json($cities);
    }
}


