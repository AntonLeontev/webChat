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

	Route::apiResource('chats.messages', ChatController::class)->shallow()
		->only(['store', 'index'])
		->middleware(MarkAllMessagesAsRead::class);
		
	Route::post('/chats/{chat}/messages/image', [ChatController::class, 'storeImageMessage'])
		->middleware(MarkAllMessagesAsRead::class);
	Route::post('/chats/{chat}/messages/document', [ChatController::class, 'storeDocumentMessage'])
		->middleware(MarkAllMessagesAsRead::class);

	Route::get('chats/{chat}/messages/{offset}', [ChatController::class, 'messagesOffset'])
		->whereNumber('offset');
});

Auth::routes();
