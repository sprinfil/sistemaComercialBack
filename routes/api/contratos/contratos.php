<?php

use App\Http\Controllers\Api\ContratoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // CONTRATOS
    Route::controller(ContratoController::class)->group(function () {
        Route::get("/contratos", "index");
        Route::post("/contratos/create", "store");
        Route::put("/contratos/update/{id}", "update");
        Route::put("/contratos/restore/{id}", "restaurarDato");
        Route::get("/contratos/consulta/{id}", "showPorToma");
        Route::get("/contratos/consultaFolio/{folio}/{ano?}", "showPorFolio");
        //log delete significa borrado logico
        Route::delete("/contratos/log_delete/{id}", "destroy");

        //Cotizaciones
        Route::prefix('contratos')->group(function (){
            Route::get("/cotizacion", "indexCotizacion");
            Route::get("/cotizacion/show", "showCotizacion");
            Route::post("/cotizacion/create", "crearCotizacion");
            Route::put("/cotizacion/update/{id}", "terminarCotizacion");
            Route::delete("/cotizacion/log_delete/{id}", "destroyCot");
            Route::put("/cotizacion/restore/{id}", "restaurarCot");

            Route::prefix('cotizacion')->group(function (){
                Route::get("/detalle", "indexCot");
                Route::get("/detalle/show", "showCotDetalle");
                Route::post("/detalle/create", "crearCotDetalle");
                Route::delete("/detalle/log_delete/{id}", "destroyCotDetalle");
                Route::put("/detalle/restore/{id}", "restaurarCotDetalle");
            });
            //Detalle de cotizacion 
        });
    });
});