<?php

namespace App\Http\Controllers;

use App\Services\Telegram\TelegramService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TelegramController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

	public function webhook(Request $request, TelegramService $service)
	{
		if (!is_null($request->json('message.entities')) && $request->json('message.entities.0.type') === 'bot_command') {
			if ($request->json('message.text') === '/start') {
				$service->createChat($request->json('message.chat.id'));
			}

			return;
		}

		Log::debug('telegram', [$request->message]);
		return response()->json();
	}
}
