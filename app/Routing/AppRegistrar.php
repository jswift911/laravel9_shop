<?php

namespace App\Routing;

use App\Contracts\RouteRegistrar;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ThumbnailController;
use Illuminate\Contracts\Routing\Registrar;
use Illuminate\Support\Facades\Route;

class AppRegistrar implements RouteRegistrar
{
    public function map(Registrar $registrar): void
    {
        Route::middleware('web')
            ->group(function () {
                Route::get('/', HomeController::class)->name('home');

                //Библиотека intervention для картинок
                Route::get('/storage/images/{dir}/{method}/{size}/{file}', ThumbnailController::class)
                    ->where('method', 'resize|crop|fit')
                    //Размер 100х100
                    ->where('size','\d+x\d+')
                    ->where('file', '.+\.(png|jpg|gif|jpeg)$')
                    ->name('thumbnail');
            });
    }

}
