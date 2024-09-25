<?php

namespace App\Http\Controllers;

use App\Services\Telegram\TelegramService;
use SergiX44\Nutgram\Nutgram;

class TelegramController extends Controller
{
    public function webhook(Nutgram $bot)
    {
        $bot->run();
    }

    public function getFileUrl(string $id, TelegramService $service)
    {
        return redirect($service->getDownloadLink($id));
    }
}
