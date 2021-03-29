<?php

namespace KameCode\Image;

use Illuminate\Support\ServiceProvider;

class KameImageServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(Providers\RouteServiceProvider::class);

        $this->mergeConfigFrom(
            __DIR__ . '/../config/kameimage.php', 'kameimage'
        );
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/kameimage.php' => config_path('kameimage.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../public' => public_path('vendor/kameimage'),
        ], 'public');

        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'migrations');

        $this->publishes([
            __DIR__ . '/../resources/views' => resource_path('views/vendor/kameimage'),
        ], 'components');

        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
