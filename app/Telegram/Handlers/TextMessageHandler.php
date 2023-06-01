<?php

namespace App\Telegram\Handlers;

use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\DB;
use SergiX44\Nutgram\Nutgram;

class TextMessageHandler
{
    public function __invoke(Nutgram $bot): void
    {
		DB::transaction(function () use ($bot) {
			Message::query()->create([
				'chat_id' => $bot->message()->chat->id,
				'message_id' => $bot->message()->message_id,
				'new_id' => $bot->message()->chat->id,
				'text' => $bot->message()->text,
				'from' => $bot->message()->from->id,
			]);
	
			Chat::where(['id' => $bot->message()->chat->id])
				->update(['last_message' => now()]);
		}, 3);
    }
}
