<?php

use App\Http\Controllers\Api\TarifaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // tarifa
    Route::controller(TarifaController::class)->group(function(){
        Route::post("/tarifa/create","store");
        Route::get("/tarifa","index");
        Route::get("/tarifa/show/{id}","show");
        Route::put("/tarifa/update/{id}","update");
        Route::put("/actualizar-tarifa","actualizarEstadoTarifa");
        Route::delete("/tarifa/log_delete/{id}","destroy");
        Route::put("tarifa/restaurar/{id}","restaurarTarifa");
    });

    // tarifa concepto detalle
    Route::controller(TarifaController::class)->group(function(){
        Route::post("/tarifaConceptoDetalle/create","storeTarifaConceptoDetalle");
        Route::get("/tarifaConceptoDetalle","indexTarifaConceptoDetalle");
        Route::get("/tarifaConceptoDetalle/show/{id}","showTarifaConceptoDetalle");
        Route::put("/tarifaConceptoDetalle/update/{id}","updateTarifaConceptoDetalle");
        Route::get("/tarifaConceptoDetalle/conceptoAsociado/{id}","tarifaPorConceptoAsociado");

        // consulta tarifa conceptos por id
        Route::get("/tarifaConceptoDetalle/{tarifa_id}","get_conceptos_detalles_by_tarifa_id");
        Route::get("/tarifaServicioDetalle/{tarifa_id}","get_servicios_detalles_by_tarifa_id");
    });

    // tarifa Servicio detalle
    Route::controller(TarifaController::class)->group(function(){
        Route::post("/tarifaServicioDetalle/create","storeTarifaServicioDetalle");

        Route::get("/tarifaServicioDetalle/show/{id}","showTarifaServicioDetalle");
        Route::put("/tarifaServicioDetalle/update/{id}","updateTarifaServicioDetalle");
    });
});