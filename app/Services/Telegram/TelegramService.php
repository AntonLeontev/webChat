<?php

namespace App\Services\Telegram;

use App\Models\Chat;
use App\Models\Message as DBMessage;
use Illuminate\Support\Facades\DB;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Message\Message;

class TelegramService
{
	public function __construct(public Nutgram $bot)
	{	  
	}

	public function storeMessage(Message $message): void
	{
		DB::transaction(function () use ($message) {
			DBMessage::query()->create([
				'chat_id' => $message->chat->id,
				'message_id' => $message->message_id,
				'text' => $message->text,
				'from' => $message->from->id,
			]);

			$botId = str(env('TELEGRAM_TOKEN'))->before(':')->value();
			if ($botId == $message->from->id) {
				$is_unread = false;
			} else {
				$is_unread = true;
			}
	
			Chat::where(['id' => $message->chat->id])
				->update([
					'last_message' => now(),
					'is_unread' => $is_unread,
				]);
		}, 3);
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
