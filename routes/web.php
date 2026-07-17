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
Route::post('/study/create', [StudyRoomController::class, 'store'])->name('study.store');
Route::post('/study/{id}/join', [StudyRoomController::class, 'processPassword'])->name('study.password');
Route::delete('/study/{id}', [StudyRoomController::class, 'destroy'])->name('study.destroy');
Route::get('/study/{id}', [StudyRoomController::class, 'show'])->name('study.show')->middleware(\App\Http\Middleware\CheckTablePassword::class);
Route::post('/study/search', [StudyRoomController::class, 'search'])->name('study.search');
Route::get('/study/{id}/messages', [StudyRoomController::class, 'getMessages'])->name('study.messages.get');
Route::get('/study/{id}/users', [StudyRoomController::class, 'getUsers'])->name('study.users.get');
Route::get('/study/{id}/files', [StudyRoomController::class, 'getFiles'])->name('study.files.get');
Route::post('/study/{id}/files', [StudyRoomController::class, 'uploadFile'])->name('study.files.upload');

Route::get('/cafe', [ChatController::class, 'index'])->name('cafe.index');
Route::view('/music', 'music')->name('music');
Route::get('/music/search', [MusicController::class, 'searchMusic'])->name('music.search');
// Profile routes
Route::get('/dossier/{id}', [ProfileController::class, 'showPublic'])->name('profile.public');

Route::middleware(['auth'])->group(function () {
    Route::get('/music/mixtapes', [MusicController::class, 'getMixtapes'])->name('music.mixtapes.get');
    Route::post('/music/mixtapes', [MusicController::class, 'saveMixtape'])->name('music.mixtapes.save');
    Route::delete('/music/mixtapes/{id}', [MusicController::class, 'deleteMixtape'])->name('music.mixtapes.delete');

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/cafe', [ChatController::class, 'store'])->name('cafe.store')->middleware('throttle:15,1');
    Route::get('/barista', [ChatController::class, 'barista'])->name('barista');
    
    // Store & Inventory Routes
    Route::get('/store', [\App\Http\Controllers\InventoryController::class, 'index'])->name('store.index');
    Route::post('/store/purchase', [\App\Http\Controllers\InventoryController::class, 'purchase'])->name('store.purchase');
    Route::post('/inventory/toggle', [\App\Http\Controllers\InventoryController::class, 'toggleEquip'])->name('inventory.toggle');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [\App\Http\Controllers\AdminController::class, 'index'])->name('dashboard');
    Route::post('/users/{id}/ban', [\App\Http\Controllers\AdminController::class, 'toggleBan'])->name('users.ban');
    Route::post('/tables', [\App\Http\Controllers\AdminController::class, 'storeTable'])->name('tables.store');
    Route::put('/tables/{id}', [\App\Http\Controllers\AdminController::class, 'updateTable'])->name('tables.update');
    Route::delete('/tables/{id}', [\App\Http\Controllers\AdminController::class, 'deleteTable'])->name('tables.delete');
});

require __DIR__.'/auth.php';