<?php

use App\Http\Controllers\AiChatController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\GratitudeController;
use App\Http\Controllers\JournalController;
use App\Http\Controllers\PinLockController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StatsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'pin.unlocked'])->name('dashboard');

// PIN lock (di luar middleware pin.unlocked)
Route::middleware('auth')->group(function () {
    Route::get('/pin/lock', [PinLockController::class, 'showLock'])->name('pin.lock');
    Route::post('/pin/unlock', [PinLockController::class, 'unlock'])->name('pin.unlock');
    Route::post('/pin/relock', [PinLockController::class, 'logoutPin'])->name('pin.relock');

    Route::middleware('pin.unlocked')->group(function () {
        Route::get('/statistik', [StatsController::class, 'index'])->name('stats.index');

        Route::get('/curhat-ai', [AiChatController::class, 'index'])->name('ai-chat.index');
        Route::post('/curhat-ai', [AiChatController::class, 'send'])->name('ai-chat.send');
        Route::delete('/curhat-ai', [AiChatController::class, 'clear'])->name('ai-chat.clear');

        Route::resource('journals', JournalController::class);

        Route::get('/gratitude', [GratitudeController::class, 'index'])->name('gratitude.index');
        Route::post('/gratitude', [GratitudeController::class, 'store'])->name('gratitude.store');
        Route::delete('/gratitude/{gratitudeItem}', [GratitudeController::class, 'destroy'])->name('gratitude.destroy');

        Route::get('/pengaturan-pin', [PinLockController::class, 'edit'])->name('pin.edit');
        Route::put('/pengaturan-pin', [PinLockController::class, 'update'])->name('pin.update');
        Route::delete('/pengaturan-pin', [PinLockController::class, 'destroy'])->name('pin.destroy');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
