<?php

namespace App\Telegram\Handlers;

use App\Models\Chat;
use App\Models\Message;
use App\Services\Telegram\TelegramService;
use Illuminate\Support\Facades\DB;
use SergiX44\Nutgram\Nutgram;

class TextMessageHandler
{
    public function __invoke(Nutgram $bot): void
    {
		$service = app(TelegramService::class);
		
		$service->storeMessage($bot->message());
    }
}
