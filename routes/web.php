<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\TelegramController;
use App\Http\Middleware\MarkAllMessagesAsRead;
use App\Models\Chat;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/test', function() {
	dd(Chat::where('id', 415803337)->withCount('unreadMessages')->first());
});

Route::get('/register', function() {
	return to_route('login');
});
Route::get('/logout', function() {
	auth()->logout();
	return to_route('login');
});

Route::middleware('auth')->group(function () {
	Route::get('/', function () {
		return view('welcome');
	});
	
	Route::get('/telegram/files/{id}', [TelegramController::class, 'getFileUrl'])->name('telegram.getFile');
	
	Route::get('/chats', [ChatController::class, 'list'])->name('chats.index');
	Route::get('/chats/{offset}', [ChatController::class, 'chatsOffset'])->name('chats.offset')
		->whereNumber('offset');
	// Route::put('/chats/{chat}/messages/mark-read', [ChatController::class, 'markRead'])->name('chats.mark-read');
	Route::get('/chats/{chat}/messages/', [ChatController::class, 'index'])->middleware(MarkAllMessagesAsRead::class);
	
	Route::apiResource('chats.messages', ChatController::class)->shallow()->only(['store']);
	Route::get('chats/{chat}/messages/{offset}', [ChatController::class, 'messagesOffset'])
		->whereNumber('offset');
});

Auth::routes();
