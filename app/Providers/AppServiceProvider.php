<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::aliasMiddleware('ls', \App\Http\Middleware\EnsureUserIsLS::class);
        Route::aliasMiddleware('admin', \App\Http\Middleware\EnsureUserIsAdmin::class);
        Route::aliasMiddleware('owner', \App\Http\Middleware\EnsureUserIsOwner::class);
        Route::aliasMiddleware('sdc', \App\Http\Middleware\EnsureUserIsSDC::class);
        Route::aliasMiddleware('ro', \App\Http\Middleware\EnsureUserIsRO::class);
    }
}