<?php

namespace App\Providers;

use App\Models\Role;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class RolesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        try {
            Role::query()->get()->map(function(Role $role) {
                Gate::define($role->name, function($user) use ($role) {
                    /** @var \App\Models\User $user */
                    /** @var \App\Models\Role $role */
                    return $user->hasRole($role->name);
                });
            });
        }
        catch (\Exception $e) {
            report($e);            
        }
    }
}
