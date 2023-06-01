<?php

namespace App\Telegram\Commands;

use App\Models\Chat;
use App\Services\Telegram\TelegramService;
use Illuminate\Support\Facades\Storage;
use SergiX44\Nutgram\Handlers\Type\Command;
use SergiX44\Nutgram\Nutgram;

class Start extends Command
{
    protected string $command = 'start';

    protected ?string $description = 'Start command';

	public function __construct(private TelegramService $service)
	{	  
	}

    public function handle(Nutgram $bot): void
    {
        $chat = $bot->getChat($bot->message()->chat->id);

		$photo = $this->service->downloadFile($chat->photo->small_file_id);
		$path = "chats/{$chat->id}.jpg";

		Storage::disk('images')->put($path, $photo);

		Chat::updateOrCreate(
			['id' => $chat->id],
			[
				'username' => $chat->username,
				'first_name' => $chat->first_name,
				'type' => $chat->type,
				'small_chat_photo' => Storage::disk('images')->url($path),
			]
		);

		if (empty($chat->first_name)) {
			$message = "Здравствуйте! Чем можем помочь?";
		} else {
			$message = "Здравствуйте, {$chat->first_name}! Чем можем помочь?";
		}

        $bot->sendMessage($message);
    }
}
