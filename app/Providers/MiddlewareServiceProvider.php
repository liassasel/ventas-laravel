<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Middleware\CheckSystemAvailability;
use App\Http\Middleware\AdminMiddleware;

class MiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
    {
        $router = $this->app['router'];
        
        // Register middleware aliases
        $router->aliasMiddleware('admin', AdminMiddleware::class);
        $router->aliasMiddleware('check.system.availability', CheckSystemAvailability::class);

        // Add middleware to web group
        $router->middlewareGroup('web', [
            CheckSystemAvailability::class,
        ]);
    }
}

