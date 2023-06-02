<?php

namespace App\Http\Controllers;

use SergiX44\Nutgram\Nutgram;

class TelegramController extends Controller
{
	public function webhook(Nutgram $bot)
	{
		$bot->run();
	}
}
