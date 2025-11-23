<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\ModulePolicy; // <- Agrega esta línea

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    protected $policies = [
    ];

    protected $listen = [
        \Illuminate\Auth\Events\Login::class => [
            \App\Listeners\UpdateLastLoginAt::class,
        ],
    ];

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('module-action', [ModulePolicy::class, 'canPerform']);

        //
    }
}
