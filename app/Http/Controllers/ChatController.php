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
use SergiX44\Nutgram\Telegram\Properties\ParseMode;

class ChatController extends Controller
{
    public function list(Request $request)
	{
		$chats = Chat::query()
			->whereHas('messages')
			->orderByDesc('last_message')
			->withCount('unreadMessages')
			->take(30)
			->get();

		return new ChatCollection($chats);
	}

	public function chatsOffset(int $offset)
	{
		$chats = Chat::query()
			->whereHas('messages')
			->orderByDesc('last_message')
			->withCount('unreadMessages')
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

	public function markRead(Chat $chat)
	{
		$messages = $chat->unreadMessages->pluck('id');
		
		Message::whereIn('id', $messages)->update(['is_unread' => false]);

		return response()->json();
	}

	public function store(Chat $chat, SendMessageRequest $request, TelegramService $service)
	{
		$message = $service->bot->sendMessage($request->text, $chat->id, null, ParseMode::MARKDOWN);

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
