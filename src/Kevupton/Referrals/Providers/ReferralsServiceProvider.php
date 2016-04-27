<?php namespace Kevupton\Referrals\Providers;

use Illuminate\Support\ServiceProvider;

class ReferralsServiceProvider extends ServiceProvider {

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([__DIR__.'/../../../config/Referrals.php' => config_path('referrals.php')]);
        $this->publishes([
            __DIR__.'/../../../database/migrations/' => database_path('migrations')
        ], 'migrations');
//        $this->publishes([
//            __DIR__.'/../../../database/seeds/' => database_path('seeds')
//        ], 'seeds');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
//
    }
}