<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\TelegramController;
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

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/test', function () {
//     dd(config('nutgram.token'));
// });

Route::get('/telegram/files/{id}', [TelegramController::class, 'getFileUrl'])->name('telegram.getFile');

Route::get('/chats', [ChatController::class, 'list'])->name('chats.index');
Route::put('/chats/{chat}', [ChatController::class, 'update'])->name('chats.update');
Route::apiResource('chats.messages', ChatController::class)->shallow()->only(['index', 'store']);
