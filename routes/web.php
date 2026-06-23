<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudyRoomController;
use App\Http\Controllers\ChatController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/study', [StudyRoomController::class, 'index'])->name('study.index');

Route::middleware(['auth'])->group(function () {
    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
});

require __DIR__.'/auth.php';