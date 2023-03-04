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
        FacadesRateLimiter::for('prova', fn()=> Limit::perMinute(15));
        FacadesRateLimiter::for('cargos', fn()=> Limit::perMinute(15));
        FacadesRateLimiter::for('orgaos', fn()=> Limit::perMinute(15));
        FacadesRateLimiter::for('questoes', fn()=> Limit::perMinute(15));
        FacadesRateLimiter::for('editais', fn()=> Limit::perMinute(15));
        FacadesRateLimiter::for('comentario', fn()=> Limit::perMinute(15));
        FacadesRateLimiter::for('downloads', fn()=> Limit::perMinute(20));

        FacadesRateLimiter::for('assuntosGPT', fn()=> Limit::perMinute(10));
        FacadesRateLimiter::for('escolaridadesGPT', fn()=> Limit::perMinute(10));
        FacadesRateLimiter::for('orgaosGPT', fn()=> Limit::perMinute(10));
        FacadesRateLimiter::for('areasGPT', fn()=> Limit::perMinute(10));
        FacadesRateLimiter::for('bancasGPT', fn()=> Limit::perMinute(10));
        FacadesRateLimiter::for('cargosGPT', fn()=> Limit::perMinute(10));
        FacadesRateLimiter::for('materiasGPT', fn()=> Limit::perMinute(10));
        FacadesRateLimiter::for('profissoesGPT', fn()=> Limit::perMinute(10));
        FacadesRateLimiter::for('comentariosGPT', fn()=> Limit::perMinute(10));

        
    }
}
