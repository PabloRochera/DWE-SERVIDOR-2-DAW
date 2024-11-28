<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\FileController;
use Illuminate\Support\Facades\Route;

// Página de bienvenida
Route::get('/', function () {
    return view('welcome');
});

// Redirigir al listado de archivos al iniciar sesión
Route::get('/dashboard', function () {
    return redirect()->route('files.index');
})->middleware(['auth', 'verified'])->name('dashboard');

// Rutas protegidas para autenticación
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    
    // Gestión de Archivos
    Route::get('/files', [FileController::class, 'index'])->name('files.index');
    Route::post('/files/upload', [FileController::class, 'upload'])->name('files.upload');
    Route::get('/files/download/{id}', [FileController::class, 'download'])->name('files.download');
    Route::delete('/files/{id}', [FileController::class, 'delete'])->name('files.delete');
    Route::post('/files/{id}/restore', [FileController::class, 'restore'])->name('files.restore');
    Route::delete('/files/{id}/force', [FileController::class, 'forceDelete'])->name('files.forceDelete');
    Route::get('/files/{id}/share', [FileController::class, 'share'])->name('files.share');
    Route::get('/files/shared/{token}', [FileController::class, 'shared'])->name('files.shared');
    Route::get('/files/{id}/preview', [FileController::class, 'preview'])->name('files.preview');
    Route::post('/files/{id}/update', [FileController::class, 'updateFile'])->name('files.update');
    Route::get('/files/{id}/versions', [FileController::class, 'versions'])->name('files.versions');
    Route::get('/files/search', [FileController::class, 'search'])->name('files.search');
});

// Archivos de autenticación
require __DIR__.'/auth.php';
