<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{

    public function register() : void
    {
        $this->app->register(
            \Domain\Auth\Providers\AuthServiceProvider::class,
        );
    }

}
