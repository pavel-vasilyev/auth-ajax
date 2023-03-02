<?php

namespace PavelVasilyev\AuthAjax\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use PavelVasilyev\AuthAjax\View\Components\Layouts\Modal;

class PackageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../config/auth.php', 'auth');
    }

    public function boot()
    {
        Blade::component('modal', Modal::class);
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../views', 'auth-ajax');
        $this->loadJsonTranslationsFrom(__DIR__.'/../lang');
        /*$this->publishes([
            __DIR__.'/../lang' => $this->app->langPath(),
        ], 'lang');*/
        $this->publishes([
            __DIR__.'/../Models' => app_path('Models'),
        ], 'user');
        $this->publishes([
            __DIR__.'/../fonts' => public_path('fonts'),
        ], 'fonts');
        $this->publishes([
            __DIR__.'/../sass' => resource_path('vendor/auth-ajax/sass'),
        ], 'sass');
        $this->publishes([
            __DIR__.'/../js' => resource_path('vendor/auth-ajax/js'),
        ], 'js');
        $this->publishes([
            __DIR__.'/../Middleware' => app_path('Http/Middleware'),
        ], 'middleware');
    }
}
