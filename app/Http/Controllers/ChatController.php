<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatCollection;
use App\Http\Resources\MessageCollection;
use App\Models\Chat;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function list()
	{
		$chats = Chat::query()
			->whereHas('messages')
			->orderByDesc('last_message')
			->get();

		return new ChatCollection($chats);
	}

	public function index(Chat $chat, Request $request)
	{
		return new MessageCollection($chat->messages);
	}

	public function store(Chat $chat)
	{
		
	}
}
