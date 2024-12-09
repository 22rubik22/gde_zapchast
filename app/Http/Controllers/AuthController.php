<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\UserAddress;
use App\Models\UserPhoneNumber; // Импортируем модель UserPhoneNumber
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailVerification;

class AuthController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
{
    // Валидация для email, password, username, адреса и номера телефона
    $request->validate([
        'email' => 'required|string|email|max:255|unique:users',
        'password' => 'required|string|min:8|confirmed',
        'username' => 'required|string|max:255',
        'address_line' => 'required|string|max:255',
        'city' => ['required', 'string'],
        'phone' => ['required', 'string', 'max:19', 'unique:users_phone_number,number_1'],
        'user_status' => 'required|integer', // Добавляем валидацию для user_status
    ]);

    // Создание пользователя
    $user = User::create([
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'username' => $request->username,
        'city' => $request->city,
        'user_status' => $request->user_status, // Устанавливаем значение user_status
        'email_verified_at' => null, // По умолчанию email не подтвержден
    ]);

    // Создание записи в UserAddress
    UserAddress::create([
        'user_id' => $user->id,
        'address_line' => $request->address_line,
        'city' => $request->city,
    ]);

    // Создание записи в UserPhoneNumber
    UserPhoneNumber::create([
        'user_id' => $user->id,
        'number_1' => $request->phone,
    ]);

    // Отправка письма с подтверждением
    Mail::to($user->email)->send(new EmailVerification($user));

    // Перенаправление на страницу с уведомлением о подтверждении почты
    return redirect()->route('verification.notice')->with('success', 'Регистрация успешна! Пожалуйста, подтвердите ваш email.');
}

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        // Валидация для email и password
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);
    
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember'); // Получаем значение чекбокса "Запомнить меня"

        if (Auth::attempt($credentials, $remember)) {
            // Перенаправление на страницу профиля
            return redirect()->route('user.show', ['id' => Auth::id()])->with('success', 'Вы успешно вошли в систему!');
        }
    
        return back()->withErrors(['email' => 'Неверные учетные данные.']);
    }


    public function logout(Request $request)
{
    Auth::logout();
    
    // Перенаправление на главную страницу или другую страницу
    return redirect('/')->with('success', 'Вы вышли из системы.');
}
}