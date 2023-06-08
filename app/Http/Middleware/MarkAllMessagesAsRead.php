<?php

namespace App\Http\Middleware;

use App\Models\Message;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class MarkAllMessagesAsRead
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

	public function terminate(Request $request, Response $response): void
    {
		$messages = $request->chat->unreadMessages->pluck('id');
		
		Message::whereIn('id', $messages)->update(['is_unread' => false]);
    }
}
