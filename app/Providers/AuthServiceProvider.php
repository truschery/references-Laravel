<?php

namespace App\Providers;

// use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */

      protected $policies = [
         'App\Model' => 'App\Policies\Modelpolicy',
     ];


    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
     public function boot(): void
    {
        $this->registerPolicies();
//         Passport::routes();
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
    //         Passport::routes();
    //         Passport::ignoreRoutes();
    }
}
