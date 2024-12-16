<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\TechnicalServiceController;
use App\Http\Controllers\SystemSettingController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\DashboardController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Rutas de productos
Route::resource('products', ProductController::class);

// Rutas de usuarios
Route::resource('users', UserController::class);

// Rutas de Tecnicos
Route::resource('technical_services', TechnicalServiceController::class);


Route::resource('stores', StoreController::class);


Route::resource('sales', SaleController::class);

// Asegúrese de que todas las rutas web estén dentro de un grupo web
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('products.index');
    });
    
    // Rutas de productos (ya existentes)
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // Categories routes - explicitly define all routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Rutas de usuarios
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
    Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
    Route::post('/users', [UserController::class, 'store'])->name('users.store');
    Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
    Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
    Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
    Route::post('/users/deactivate-non-admins', [UserController::class, 'deactivateNonAdmins'])->name('users.deactivateNonAdmins');

    // Tech Route
    Route::get('/services', [TechnicalServiceController::class, 'index'])->name('technical_service.index');
    Route::get('/services/create', [TechnicalServiceController::class, 'create'])->name('technical_service.create');
    Route::post('/services', [TechnicalServiceController::class, 'store'])->name('technical_service.store');
    Route::get('/services/{technicalService}/edit', [TechnicalServiceController::class, 'edit'])->name('technical_service.edit');
    Route::put('/services/{technicalService}', [TechnicalServiceController::class, 'update'])->name('technical_service.update');
    Route::delete('/services/{technicalService}', [TechnicalServiceController::class, 'destroy'])->name('technical_service.destroy');

    // Settings Route, only accessible by admin
    Route::group(['middleware' => ['auth']], function () {
        Route::get('/settings', [SystemSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [SystemSettingController::class, 'update'])->name('settings.update');
        Route::post('/settings/deactivate-non-admins', [SystemSettingController::class, 'deactivateNonAdmins'])
            ->name('settings.deactivateNonAdmins');
    });
});

