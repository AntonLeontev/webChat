<?php

namespace App\Telegram\Commands;

use App\Models\Chat;
use App\Services\Telegram\TelegramService;
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

        if (! is_null($chat->photo)) {
            $photo = route('telegram.getFile', $chat->photo?->small_file_id);
        } else {
            $photo = '/default_user.jpg';
        }

        Chat::updateOrCreate(
            ['id' => $chat->id],
            [
                'username' => $chat->username,
                'first_name' => $chat->first_name,
                'type' => $chat->type,
                'small_chat_photo' => $photo,
            ]
        );

        if (empty($chat->first_name)) {
            $message = 'Здравствуйте! Чем можем помочь?';
        } else {
            $message = "Здравствуйте, {$chat->first_name}! Чем можем помочь?";
        }

        $bot->sendMessage($message);
    }
}
