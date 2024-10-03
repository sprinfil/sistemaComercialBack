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
            'cargo_directo' => 'App\Models\CargoDirecto',
            'concepto_aplicable' => 'App\Models\ConceptoAplicable',
            'convenio_catalogo' => 'App\Models\ConvenioCatalogo',
            'descuento_catalogo' => 'App\Models\DescuentoCatalogo',
            'ajuste_catalogo' => 'App\Models\AjusteCatalogo',
            'libro' => 'App\Models\Libro',
            'ruta' => 'App\Models\Ruta',
            'datos_fiscales' => 'App\Models\DatoFiscal',
            'asignacion_geografica' => 'App\Models\AsignacionGeografica',
            'factibilidad' => 'App\Models\Factibilidad',
            'pago' => 'App\Models\Pago',
            'abono' => 'App\Models\Abono',
            'caja' => 'App\Models\Caja',
            'orden_trabajo' => 'App\Models\OrdenTrabajo',
            'cfdi' => 'App\Models\Cfdi',
            'ajuste' => 'App\Models\Ajuste',
            //'descuento' => 'App\Models\DescuentoAsociado',
            'letra' => 'App\Models\Letra',
            'carga_trabajos' => 'App\Models\CargaTrabajo',
            'descuento_asociado' => 'App\Models\DescuentoAsociado',
            'multa' => 'App\Models\Multa'
        ]);
    }
}
