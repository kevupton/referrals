<?php namespace Kevupton\Referrals\Providers;

use Illuminate\Database\Eloquent\Model;
use Kevupton\LaravelPackageServiceProvider\ServiceProvider;
use Kevupton\Referrals\Facades\Referrals\ReferralsFacade;
use Kevupton\Referrals\Observers\ReferralObserver;
use Kevupton\Referrals\Referrals;

class ReferralsServiceProvider extends ServiceProvider
{
    const SINGLETON = 'referrals';

    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $user = ref_user();

        if (is_a($user, Model::class, true)) {
            $user::observe(ReferralObserver::class);
        }

        $this->registerConfig('/../../../config/Referrals.php', REFERRAL_CONFIG . '.php');
        $this->loadMigrationsFrom(__DIR__ . '/../../../database/migrations');
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(self::SINGLETON, function () {
            return new Referrals();
        });

        $this->registerAlias(ReferralsFacade::class, 'Referrals');

        $this->mergeConfigFrom(
            __DIR__ . '/../../../config/config.php', REFERRAL_CONFIG
        );
    }
}