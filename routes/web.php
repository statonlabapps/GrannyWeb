<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\AnalysisController;

Route::get('/', function () {
    return Inertia::render('LandingPage', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('landing');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/apples', function () {
    return Inertia::render('ApplesPage');
})->middleware(['auth', 'verified'])->name('apples');

Route::get('/pears', function () {
    return Inertia::render('PearsPage');
})->middleware(['auth', 'verified'])->name('pears');

Route::get('/new-analysis', function () {
    return Inertia::render('NewAnalysisPage');
})->middleware(['auth', 'verified'])->name('new-analysis');

Route::get('/segmentation/{analysis_id}', [AnalysisController::class, 'segmentation'])->middleware(['auth', 'verified']);
Route::get('/fruit-rating/{analysis_id}', [AnalysisController::class, 'fruitRating'])->middleware(['auth', 'verified']);
Route::get('/summary/{analysis_id}', [AnalysisController::class, 'summary'])->middleware(['auth', 'verified']);



Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
