<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendDocumentRequest;
use App\Http\Requests\SendImageRequest;
use App\Http\Requests\SendMessageRequest;
use App\Http\Resources\ChatCollection;
use App\Http\Resources\MessageCollection;
use App\Models\Chat;
use App\Models\Message;
use App\Services\Telegram\TelegramService;
use Illuminate\Http\Request;
use SergiX44\Nutgram\Telegram\Properties\ParseMode;
use SergiX44\Nutgram\Telegram\Types\Internal\InputFile;

class ChatController extends Controller
{
    public function list(Request $request)
    {
        $chats = Chat::query()
            ->whereHas('messages')
            ->orderByDesc('last_message')
            ->withCount('unreadMessages')
            ->take(30)
            ->get();

        return new ChatCollection($chats);
    }

    public function chatsOffset(int $offset)
    {
        $chats = Chat::query()
            ->whereHas('messages')
            ->orderByDesc('last_message')
            ->withCount('unreadMessages')
            ->take(30)
            ->skip($offset)
            ->get();

        return new ChatCollection($chats);
    }

    public function index(Chat $chat)
    {
        $messages = Message::where('chat_id', $chat->id)
            ->orderByDesc('created_at')
            ->with('user')
            ->paginate(20);

        return new MessageCollection($messages);
    }

    public function markRead(Chat $chat)
    {
        $messages = $chat->unreadMessages->pluck('id');

        Message::whereIn('id', $messages)->update(['is_unread' => false]);

        return response()->json();
    }

    public function store(Chat $chat, SendMessageRequest $request, TelegramService $service)
    {
        $message = $service->bot->sendMessage($request->text, $chat->id, null, ParseMode::MARKDOWN);

        $message = $service->storeMessage($message);

        return response()->json($message);
    }

    public function storeImageMessage(Chat $chat, SendImageRequest $request, TelegramService $service)
    {
        $message = $service->bot->sendPhoto(new InputFile($request->image->path()), $chat->id);

        $message = $service->storeMessage($message);

        return response()->json($message);
    }

    public function storeDocumentMessage(Chat $chat, SendDocumentRequest $request, TelegramService $service)
    {
        $file = $request->document;
        $file = new InputFile($file->path(), $file->getClientOriginalName());

        $message = $service->bot->sendDocument($file, $chat->id);

        $message = $service->storeMessage($message);

        return response()->json($message);
    }

    public function messagesOffset(Chat $chat, int $offset)
    {
        $messages = Message::where('chat_id', $chat->id)
            ->orderByDesc('created_at')
            ->with('user')
            ->skip($offset)
            ->take(20)
            ->get();

        return new MessageCollection($messages);
    }
}
