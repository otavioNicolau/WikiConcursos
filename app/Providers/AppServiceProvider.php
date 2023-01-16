<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Queue\Middleware\RateLimited;
use Illuminate\Support\Facades\RateLimiter as FacadesRateLimiter;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        FacadesRateLimiter::for('cargos', fn()=> Limit::perMinute(50));
        FacadesRateLimiter::for('orgaos', fn()=> Limit::perMinute(50));
        FacadesRateLimiter::for('questoes', fn()=> Limit::perMinute(30));
        FacadesRateLimiter::for('editais', fn()=> Limit::perMinute(100));
        FacadesRateLimiter::for('comentario', fn()=> Limit::perMinute(100));

        FacadesRateLimiter::for('assuntosGPT', fn()=> Limit::perMinute(10));
        
    }
}
