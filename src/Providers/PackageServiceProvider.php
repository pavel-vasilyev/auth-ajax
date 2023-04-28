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
        //$this->loadViewsFrom(__DIR__.'/../views', 'auth-ajax');
        //$this->loadJsonTranslationsFrom(__DIR__.'/../lang');
        $this->publishes([
            __DIR__.'/../../database/seeders' => database_path('seeders'),
        ], 'seed');
        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath(),
        ], 'lang');
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
        $this->publishes([
            __DIR__.'/../View/Components/layouts' => app_path('View/Components/layouts'),
        ], 'layouts');
        $this->publishes([
            __DIR__.'/../views/components' => resource_path('views/components'),
        ], 'components');
        $this->publishes([
            __DIR__.'/../views/auth' => resource_path('views/auth'),
        ], 'forms');
        $this->publishes([
            __DIR__.'/../views/example.blade.php' => resource_path('views/example.blade.php'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../views/errors' => resource_path('views/errors'),
        ], 'errors');
        $this->publishes([
            __DIR__.'/../Notifications' => app_path('Notifications'),
        ], 'notifications');
        $this->publishes([
            __DIR__.'/../Controllers/ExampleController.php' => app_path('Http/Controllers/ExampleController.php'),
        ], 'controller');
    }
}
