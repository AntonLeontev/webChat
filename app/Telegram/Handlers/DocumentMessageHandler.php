<?php

namespace App\Telegram\Handlers;

use App\Services\Telegram\TelegramService;
use SergiX44\Nutgram\Nutgram;

class DocumentMessageHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $service = app(TelegramService::class);

        $service->storeMessage($bot->message());
    }
}
