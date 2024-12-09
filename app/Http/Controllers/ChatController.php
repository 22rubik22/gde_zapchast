<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Advert;
use App\Models\Chat;
use App\Models\Message;

class ChatController extends Controller
{
    //
    public function openChat(Request $request, Advert $advert)
    {
        $user = auth()->user();
        
        // Проверяем, существует ли уже чат между пользователями по этому объявлению
        $chat = Chat::where('advert_id', $advert->id)
                    ->where(function($query) use ($user, $advert) {
                        $query->where('user1_id', $user->id)
                              ->where('user2_id', $advert->user_id);
                    })->orWhere(function($query) use ($user, $advert) {
                        $query->where('user1_id', $advert->user_id)
                              ->where('user2_id', $user->id);
                    })->first();

        if (!$chat) {
            // Если чат не существует, создаем новый
            $chat = Chat::create([
                'user1_id' => $user->id,
                'user2_id' => $advert->user_id,
                'advert_id' => $advert->id,
            ]);
        }

        // Перенаправляем на страницу чата
        return redirect()->route('chat.show', ['chat' => $chat]);
    }

    // отображение  всех чатов 
    public function index()
    {
        $user = auth()->user();

        // Получаем все чаты текущего пользователя с последним сообщением и количеством непрочитанных сообщений
        $userChats = Chat::where('user1_id', $user->id)
                         ->orWhere('user2_id', $user->id)
                         ->with(['user1', 'user2', 'last_message'])
                         ->get()
                         ->map(function($chat) use ($user) {
                             $chat->unread_count = $chat->messages()->where('user_id', '!=', $user->id)->where('is_read', false)->count();
                             return $chat;
                         })
                         ->sortByDesc(function($chat) {
                             return $chat->last_message ? $chat->last_message->created_at : $chat->created_at;
                         });

        // Получаем или создаем чат с техподдержкой
        $supportChat = $this->getOrCreateSupportChat($user);

        return view('chat.index', compact('userChats', 'supportChat'));
    }

    private function getOrCreateSupportChat($user)
    {
        // Ищем пользователя с user_status равным 2
        $supportUser = \App\Models\User::where('user_status', 2)->first();
    
        if (!$supportUser) {
            // Если пользователь с user_status = 2 не найден, возвращаем null или бросаем исключение
            return null; // или throw new \Exception('Support user not found');
        }
    
        $supportUserId = $supportUser->id;
    
        // Проверяем, является ли текущий пользователь пользователем техподдержки
        if ($user->id === $supportUserId) {
            return null; // Возвращаем null, чтобы не создавать чат с самим собой
        }
    
        // Проверяем, существует ли уже чат между пользователем и техподдержкой
        $chat = Chat::where(function($query) use ($user, $supportUserId) {
                        $query->where('user1_id', $user->id)
                              ->where('user2_id', $supportUserId);
                    })->orWhere(function($query) use ($user, $supportUserId) {
                        $query->where('user1_id', $supportUserId)
                              ->where('user2_id', $user->id);
                    })->first();
    
        if (!$chat) {
            // Если чат не существует, создаем новый
            $chat = Chat::create([
                'user1_id' => $user->id,
                'user2_id' => $supportUserId,
                'advert_id' => 1111, // Устанавливаем значение 1111 для advert_id
            ]);
        }
    
        return $chat;
    }
    // отображение конкретного чата 
    // ChatController.php
    public function show(Chat $chat)
    {
        // Проверяем, имеет ли пользователь доступ к этому чату
        $user = auth()->user();
        if ($chat->user1_id !== $user->id && $chat->user2_id !== $user->id) {
            abort(403); // Доступ запрещен
        }
    
        // Получаем сообщения для данного чата с загрузкой пользователя и его аватара
        $messages = $chat->messages()->with(['user' => function ($query) {
            $query->select('id', 'username', 'avatar_url');
        }])->get();
    
        // Логирование для отладки
        \Log::info('Messages:', $messages->toArray());
    
        // Получаем объявление, связанное с чатом
        $advert = Advert::find($chat->advert_id);
    
        // Получаем все чаты текущего пользователя с последним сообщением и количеством непрочитанных сообщений
        $userChats = Chat::where('user1_id', $user->id)
                         ->orWhere('user2_id', $user->id)
                         ->with(['user1' => function ($query) {
                             $query->select('id', 'username', 'avatar_url');
                         }, 'user2' => function ($query) {
                             $query->select('id', 'username', 'avatar_url');
                         }, 'last_message'])
                         ->get()
                         ->map(function($chat) use ($user) {
                             $chat->unread_count = $chat->messages()->where('user_id', '!=', $user->id)->where('is_read', false)->count();
                             return $chat;
                         })
                         ->sortByDesc(function($chat) {
                             return $chat->last_message ? $chat->last_message->created_at : $chat->created_at;
                         });
    
        return view('chat.show', compact('chat', 'messages', 'userChats', 'advert'));
    }

    // метод для отправки сообщений:
    public function sendMessage(Request $request, Chat $chat)
    {
        $request->validate([
            'message' => 'required|string|max:255',
        ]);

        // Создаем новое сообщение
        $message = $chat->messages()->create([
            'user_id' => auth()->id(),
            'message' => $request->message,
        ]);

        // Возвращаем сообщение как JSON
        return response()->json($message->load('user'));
    }

    //метод для получения сообщений
    // ChatController.php
public function getMessages(Chat $chat)
{
    $messages = $chat->messages()->with(['user' => function ($query) {
        $query->select('id', 'username', 'avatar_url');
    }])->get();

    return response()->json(['messages' => $messages]);
}
    //статус сообщения
    public function markAsRead(Request $request, Chat $chat)
    {
        $chat->messages()->where('user_id', '!=', auth()->id())->update(['is_read' => true]);
        return response()->json(['success' => true]);
    }
}