<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {

        // Admin tiene todos los permisos
        Gate::before(function ( User $user, string $ability ) {

            if ( $user->hasRole('admin') ) {
                return true;
            }

            return null;
        });

        // Gate genérico que mapea $user->can('manage_users') a permisos de BD
        Gate::define('permission', function ( User $user, string $permissionName ) {

            return $user->hasPermission($permissionName);

        });

        Gate::define('view-dashboard', function (User $user) {
            return $user->hasPermission('dashboard_view');
        });

    }
}
