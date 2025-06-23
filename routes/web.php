<?php

use App\Http\Controllers\PomodoroController;
use Illuminate\Support\Facades\Route;

// Redirect root to pomodoro
Route::redirect('/', '/pomodoro');

Route::prefix('pomodoro')->group(function () {
    Route::get('/', [PomodoroController::class, 'index'])->name('pomodoro.index');
    Route::post('/', [PomodoroController::class, 'store'])->name('pomodoro.store');
    Route::post('/{id}/start', [PomodoroController::class, 'start'])->name('pomodoro.start');
    Route::post('/{id}/complete', [PomodoroController::class, 'complete'])->name('pomodoro.complete');
    Route::post('/{id}/cancel', [PomodoroController::class, 'cancel'])->name('pomodoro.cancel');
    Route::get('/current', [PomodoroController::class, 'getCurrentSession'])->name('pomodoro.current');
});
