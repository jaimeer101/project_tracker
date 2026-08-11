<?php

use App\Http\Controllers\API\ProjectAPIController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

Route::redirect('/', '/login');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/projects/view', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/add', [ProjectController::class, 'create'])->name('projects.create');
    Route::get('/projects/edit/{id}', [ProjectController::class, 'edit'])->name('projects.edit');

    Route::get('/projects', [ProjectAPIController::class, 'index'])->name('projects.api.index');
    Route::get('/projects/{id}', [ProjectAPIController::class, 'show'])->name('projects.api.show');
    Route::post('/projects', [ProjectAPIController::class, 'store'])->name('projects.api.create');
    Route::put('/projects/{id}', [ProjectAPIController::class, 'update'])->name('projects.api.update');
    Route::delete('/projects/{id}', [ProjectAPIController::class, 'destroy'])->name('projects.api.delete');
});

require __DIR__.'/auth.php';
