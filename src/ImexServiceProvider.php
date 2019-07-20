<?php

namespace BfAtoms\Imex;

use Illuminate\Support\ServiceProvider;

class ImexServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->make('BfAtoms\Imex\ImexController');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        $this->publishes([
            __DIR__.'/imex.php' => config_path('imex.php')
        ]);
    }
}
