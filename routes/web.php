<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HouseRecommendationController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        if (auth()->user()->role === 'admin') {
            return redirect(config('filament.path', '/admin'));
        }
        return redirect('/dashboard');
    }
    return redirect('/login');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [HouseRecommendationController::class, 'index'])->name('dashboard');
    Route::post('/dashboard', [HouseRecommendationController::class, 'compareHouses'])->name('compare.houses');
    Route::get('/dashboard/compare', [HouseRecommendationController::class, 'compareHouses'])->name('compare.houses.index');
});

Route::get('/recommendations/history', [HouseRecommendationController::class, 'userRecommendations'])
    ->middleware(['auth', 'verified'])
    ->name('recommendations.history');

Route::get('/recommendations/{recommendation}', [HouseRecommendationController::class, 'showRecommendation'])
    ->middleware(['auth', 'verified'])
    ->name('recommendations.show');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
