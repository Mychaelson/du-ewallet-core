<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Connection;

use App\Database\PgBouncerConnection;

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
        Connection::resolverFor('pgsql', function ($connection, $database, $prefix, $config) {
            return new PgBouncerConnection($connection, $database, $prefix, $config);
        });
    }
}
