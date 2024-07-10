<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use App\Models\AnomaliaCatalogo;
use App\Models\ConceptoCatalogo;
use App\Policies\AnomaliaCatalogoPolicy;
use App\Policies\ConpcetoCatalogoPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        AnomaliaCatalogo::class => AnomaliaCatalogoPolicy::class,
        ConceptoCatalogo::class => ConpcetoCatalogoPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
