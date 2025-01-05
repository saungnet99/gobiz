<?php

namespace App\Providers;

use App\Providers\MigrationConfig;
use Illuminate\Support\ServiceProvider;

class MigrationProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('App\Providers\MigrationConfig', function ($app) {
            return new MigrationConfig();
          });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
