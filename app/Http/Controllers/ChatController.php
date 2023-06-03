<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\MessageCollection;
use App\Models\Chat;
use App\Services\Telegram\TelegramService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function list(Request $request)
	{
		$chats = Chat::query()
			->whereHas('messages')
			->orderByDesc('last_message')
			->take(100)
			->get();

		return new ChatCollection($chats);
	}

	public function index(Chat $chat)
	{
		return new MessageCollection($chat->messages->slice(-20)->values());
	}

	public function update(Chat $chat, UpdateChatRequest $request)
	{
		$chat->update($request->validated());

		return response()->json();
	}

	public function store(Chat $chat, SendMessageRequest $request, TelegramService $service)
	{
		$message = $service->bot->sendMessage($request->text, $chat->id);

		$service->storeMessage($message);

		return response()->json();
	}
}
