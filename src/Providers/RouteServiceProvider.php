<?php

namespace KameCode\Image\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        extract(config('kameimage.routes'));

        Route::middleware($middlewares)
        ->prefix($prefix)
        ->namespace('KameCode\Image\Http\Controllers')
        ->name("$name.")
        ->group(function ($router) {
            $router->post('upload', 'ImageController@upload')->name('upload');
            $router->delete('{image}', 'ImageController@destroy')->name('delete');
        });
    }
}
