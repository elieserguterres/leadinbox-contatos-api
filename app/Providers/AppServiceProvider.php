<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Contato;
use App\Observers\ContatoObserver;

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

        Contato::observe(ContatoObserver::class);
    }
}
