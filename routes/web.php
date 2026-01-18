<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirecionar raiz para login se não autenticado
Route::get('/', function () {
    return auth()->check() ? redirect()->route('movies.index') : redirect()->route('login');
});

// Rotas de autenticação do Breeze
require __DIR__.'/auth.php';

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    // Dashboard padrão
    Route::get('/dashboard', function () {
        return redirect()->route('movies.index');
    })->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CRUD de Filmes
    Route::resource('movies', MovieController::class);

    // CRUD de Gêneros (se necessário)
    Route::resource('genres', GenreController::class)->except(['show']);
});

// Fallback route
Route::fallback(function () {
    return redirect()->route('login');
});
