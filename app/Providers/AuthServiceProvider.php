<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
        /**
         * If you follow laravel naming convention with a model and policy,
         * The policy will auto register with a responding model
        */
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(): void
    {
        $this->registerPolicies();

        if (Str::contains(haystack: request()->path(), needles: 'admin/')){
            Gate::before(function ($admin, $ability) {
                return $admin->hasRole('Super Admin') ? true : null;
            });
        }
    }
}

