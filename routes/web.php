<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudyRoomController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/study', [StudyRoomController::class, 'index'])->name('study.index');
Route::get('/study/{id}', [StudyRoomController::class, 'show'])->name('study.show');

Route::view('/cafe', 'cafe')->name('cafe');
Route::view('/music', 'music')->name('music');
Route::view('/barista', 'barista')->name('barista');
Route::view('/profile', 'profile')->name('profile');
Route::middleware(['auth'])->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/chat', [ChatController::class, 'index'])->name('chat.index');
    Route::post('/chat', [ChatController::class, 'store'])->name('chat.store');
});

require __DIR__.'/auth.php';