<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Relations\Relation;
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
        Relation::enforceMorphMap([
            'toma' => 'App\Models\Toma',
            'contrato' => 'App\Models\Contrato',
            'usuario' => 'App\Models\Usuario',
            'user' => 'App\Models\User',
            'cargo' => 'App\Models\Cargo',
            'concepto_aplicable' => 'App\Models\ConceptoAplicable',
            'convenio_catalogo' => 'App\Models\ConvenioCatalogo',
            'descuento_catalogo' => 'App\Models\DescuentoCatalogo',
            'ajuste_catalogo' => 'App\Models\AjusteCatalogo',
            'datos_fiscales' => 'App\Models\DatoFiscal',
        ]);
    }
}
