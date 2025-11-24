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

        $menus = [
            'menu-usuarios'      => [1],
            'menu-nadadores'     => [1, 2],
            'menu-niveles'       => [1,2],
            'menu-suscripciones' => [1,2,3],
            'menu-transacciones' => [1,2],
        ];

        foreach ($menus as $gateName => $nivelesPermitidos) {
            Gate::define($gateName, function ($user) use ($nivelesPermitidos) {
                return $user && in_array($user->idnivel, $nivelesPermitidos);
            });
        }

        Gate::define('module-action', [ModulePolicy::class, 'canPerform']);
    }
}
