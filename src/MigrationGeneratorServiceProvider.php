<?php

namespace Migrate;

use Illuminate\Support\ServiceProvider;

class MigrationGeneratorServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Публикация ресурсов
        $this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/migrate'),
        ], 'migrate-assets');
        
        $this->publishes([
            __DIR__.'/../config/migrate.php' => config_path('migrate.php'),
        ], 'config');
        
        // Загрузка маршрутов
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');
        
        // Загрузка представлений
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'migrate');
    }

    public function register()
    {
        // Регистрация команд
        /*$this->commands([
            Console\Commands\InstallCommand::class,
        ]);*/
    }
}