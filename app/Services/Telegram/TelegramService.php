<?php

namespace App\Services\Telegram;

use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Nutgram;

class TelegramService
{
	public function __construct(public Nutgram $bot)
	{	  
	}

	public function downloadFile(string $fileId): string
	{
		$link = $this->getDownloadLink($fileId);

		return file_get_contents($link);
	}

	public function getDownloadLink(string $fileId): string
	{
		$file = $this->bot->getFile($fileId);

		return sprintf(
			'https://api.telegram.org/file/bot%s/%s', 
			env('TELEGRAM_TOKEN'), 
			$file->file_path
		);
	}
}
