<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema; // <--- Importante: Importar Schema

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
        // Corrección para MySQL/MariaDB en versiones antiguas (WAMP)
        // Limita la longitud de los strings para evitar el error "max key length is 1000 bytes"
        Schema::defaultStringLength(191);
    }
}