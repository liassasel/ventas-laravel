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
use App\Http\Controllers\ProductSoldController;
use App\Http\Controllers\SupplierController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('auth.login');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Asegúrese de que todas las rutas web estén dentro de un grupo web
Route::middleware(['auth'])->group(function () {

    Route::get('/', function () {
        return redirect()->route('products.index');
    });
    
    // Rutas de productos (ya existentes)
    Route::resource('products', ProductController::class);
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    // User profile routes
    Route::get('/profile', [UserController::class, 'editProfile'])->name('users.edit-profile');
    Route::post('/profile', [UserController::class, 'updateProfile'])->name('users.update-profile');

    // Categories routes - explicitly define all routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::get('/categories/{category}/edit', [CategoryController::class, 'edit'])->name('categories.edit');
    Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

    // Sales 
    Route::resource('sales', SaleController::class);
    Route::get('/sales', [SaleController::class, 'index'])->name('sales.index');
    Route::get('/sales/create', [SaleController::class, 'create'])->name('sales.create');
    Route::post('/sales', [SaleController::class, 'store'])->name('sales.store');
    Route::get('/sales/{sale}', [SaleController::class, 'show'])->name('sales.show');

    // Tech Route
    Route::resource('technical_services', TechnicalServiceController::class);
    Route::get('/services', [TechnicalServiceController::class, 'index'])->name('technical_service.index');
    Route::get('/services/create', [TechnicalServiceController::class, 'create'])->name('technical_service.create');
    Route::post('/services', [TechnicalServiceController::class, 'store'])->name('technical_service.store');
    Route::get('/services/{technicalService}/edit', [TechnicalServiceController::class, 'edit'])->name('technical_service.edit');
    Route::put('/services/{technicalService}', [TechnicalServiceController::class, 'update'])->name('technical_service.update');
    Route::delete('/services/{technicalService}', [TechnicalServiceController::class, 'destroy'])->name('technical_service.destroy');

    // Settings Route, only accessible by admin
    Route::group(['middleware' => ['admin']], function () {

        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard/user-stats/{user}', [DashboardController::class, 'getUserStats'])->name('dashboard.user-stats');
        Route::get('/user-stats/{user}', [DashboardController::class, 'getUserStats'])->name('user.stats');



        // Ruta de tiendas
        Route::resource('stores', StoreController::class);

        // suppliers routes
        Route::resource('suppliers', SupplierController::class);
        Route::get('/suppliers', [SupplierController::class, 'index'])->name('suppliers.index');
        Route::get('/suppliers/create', [SupplierController::class, 'create'])->name('suppliers.create');
        Route::post('/suppliers', [SupplierController::class, 'store'])->name('suppliers.store');
        Route::get('/suppliers/{supplier}/edit', [SupplierController::class, 'edit'])->name('suppliers.edit');
        Route::put('/suppliers/{supplier}', [SupplierController::class, 'update'])->name('suppliers.update');
        Route::delete('/suppliers/{supplier}', [SupplierController::class, 'destroy'])->name('suppliers.destroy');


        // Ruta de ventas
        Route::get('/sales/{sale}/edit', [SaleController::class, 'edit'])->name('sales.edit');
        Route::put('/sales/{sale}', [SaleController::class, 'update'])->name('sales.update');
        Route::delete('/sales/{sale}', [SaleController::class, 'destroy'])->name('sales.destroy');
        Route::get('/sales/get-products-by-store', [SaleController::class, 'getProductsByStore'])->name('sales.getProductsByStore');

        Route::get('/products-sold', [ProductSoldController::class, 'index'])->name('products-sold.index');

        // Rutas de usuarios
        Route::resource('users', UserController::class);
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{user}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{user}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::patch('/users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggleActive');
        Route::post('/users/deactivate-non-admins', [UserController::class, 'deactivateNonAdmins'])->name('users.deactivateNonAdmins');
        Route::post('/users/activate-non-admins', [UserController::class, 'activateNonAdmins'])->name('users.activateNonAdmins');
        

        Route::get('/admin', [DashboardController::class, 'index'])->name('admin.dashboard');
        
        Route::get('/admin/settings', [SystemSettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/admin/settings', [SystemSettingController::class, 'update'])->name('admin.settings.update');
        Route::post('/admin/settings/deactivate-users', [SystemSettingController::class, 'deactivateNonAdmins'])->name('admin.settings.deactivateNonAdmins');
    });
});