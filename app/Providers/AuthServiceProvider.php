<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\Operador;
use App\Models\TipoToma;
use App\Models\AjusteCatalogo;
use App\Models\AnomaliaCatalogo;
use App\Models\ConceptoCatalogo;
use App\Models\ConvenioCatalogo;
use App\Models\DescuentoCatalogo;
use App\Models\ConstanciaCatalogo;
use App\Models\CatalogoBonificacion;
use App\Models\GiroComercialCatalogo;
use App\Policies\AjusteCatalogoPolicy;
use App\Policies\AnomaliaCatalogoPolicy;
use App\Policies\ConpcetoCatalogoPolicy;
use App\Policies\ConvenioCatalogoPolicy;
use App\Policies\OperadorCatalogoPolicy;
use App\Policies\DescuentoCatalogoPolicy;
use App\Policies\ConstanciaCatalogoPolicy;
use App\Policies\TipoDeTomaCatalogoPolicy;
use App\Policies\BonificacionCatalogoPolicy;
use App\Policies\GiroComercialCatalogoPolicy;
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
        ConceptoCatalogo::class => ConpcetoCatalogoPolicy::class,
        GiroComercialCatalogo::class => GiroComercialCatalogoPolicy::class,
        DescuentoCatalogo::class => DescuentoCatalogoPolicy::class,
        ConvenioCatalogo::class => ConvenioCatalogoPolicy::class,
        AjusteCatalogo::class => AjusteCatalogoPolicy::class,
        ConstanciaCatalogo::class => ConstanciaCatalogoPolicy::class,
        CatalogoBonificacion::class => BonificacionCatalogoPolicy::class,
        TipoToma::class => TipoDeTomaCatalogoPolicy::class,
        Operador::class => OperadorCatalogoPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
