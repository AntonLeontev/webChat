<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\MessageCollection;
use App\Models\Chat;
use App\Models\Message;
use App\Services\Telegram\TelegramService;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function list(Request $request)
	{
		$chats = Chat::query()
			->whereHas('messages')
			->orderByDesc('last_message')
			->take(30)
			->get();

		return new ChatCollection($chats);
	}

	public function chatsOffset(int $offset)
	{
		$chats = Chat::query()
			->whereHas('messages')
			->orderByDesc('last_message')
			->take(30)
			->skip($offset)
			->get();

		return new ChatCollection($chats);
	}

	public function index(Chat $chat)
	{
		$messages = Message::where('chat_id', $chat->id)
			->orderByDesc('created_at')
			->with('user')
			->paginate(20);

		return new MessageCollection($messages);
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

	public function messagesOffset(Chat $chat, int $offset)
	{
		$messages = Message::where('chat_id', $chat->id)
			->orderByDesc('created_at')
			->with('user')
			->skip($offset)
			->take(20)
			->get();

		return new MessageCollection($messages);
	}
}
