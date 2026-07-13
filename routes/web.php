<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudyRoomController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MusicController;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/study', [StudyRoomController::class, 'index'])->name('study.index');
Route::get('/study/leave', [StudyRoomController::class, 'leave'])->name('study.leave');
Route::get('/study/{id}', [StudyRoomController::class, 'show'])->name('study.show');

Route::get('/cafe', [ChatController::class, 'index'])->name('cafe.index');
Route::view('/music', 'music')->name('music');
Route::get('/music/search', [MusicController::class, 'searchMusic'])->name('music.search');
Route::view('/barista', 'barista')->name('barista');
// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/cafe', [ChatController::class, 'store'])->name('cafe.store');
});

require __DIR__.'/auth.php';