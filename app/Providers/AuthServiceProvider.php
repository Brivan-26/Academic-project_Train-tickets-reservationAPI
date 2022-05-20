<?php

namespace App\Providers;

use App\Models\Support_ticket;
use Illuminate\Auth\Access\Response;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\User;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('is_admin', function (User $user) {
            return $user->is_admin();
        });

        Gate::define('is_support', function (User $user) {
            return $user->is_support();
        });

        Gate::define('is_passenger', function (User $user) {
            return $user->is_passenger();
        });

        Gate::define('is_validator', function(User $user){
            return $user->is_validator();
        });

        Gate::define('is_supportORpassenger', function(User $user){
            return $user->hasAnyRole(["support","passenger"]);
        });
    }
}
