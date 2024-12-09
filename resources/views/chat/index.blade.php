@extends('layouts.app')
@include('components.header-seller')

@section('content')
<div class="container mx-auto mt-20">
    <div class="flex flex-col md:flex-row">
        @include('components.chat-list', ['userChats' => $userChats]) <!-- Подключаем компонент список чатов -->
        
        <div class="w-full md:w-3/4 p-4">
            <h2 class="text-2xl font-bold mb-4">Выберите чат</h2>
            <p class="text-gray-600">Пожалуйста, выберите чат из списка слева, чтобы начать общение.</p>
        </div>
    </div>
</div>
@endsection