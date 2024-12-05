<?php

use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Ruta principal
Route::get('/', function () {
    return redirect()->route('products.index');
});

// Rutas de productos
Route::resource('products', ProductController::class);

// Asegúrese de que todas las rutas web estén dentro de un grupo web
Route::middleware(['web'])->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
});

