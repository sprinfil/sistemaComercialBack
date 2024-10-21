<?php

use App\Http\Controllers\Api\TipoTomaAplicableController;
use App\Http\Controllers\Api\TipoTomaController;
use App\Models\TipoTomaAplicable;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //Tipo Toma
    Route::controller(TipoTomaController::class)->group(function () {
        Route::get("/TipoToma", "index");
        Route::post("/TipoToma/create", "store");
        Route::put("/TipoToma/update/{id}", "update");
        Route::get("/TipoToma/consulta/{nombre}", "show");
        Route::put("/TipoToma/restore/{id}", "restaurarDato");
        Route::post("TipoToma/import","importarTipoTomaTarifas");
        //log delete significa borrado logico
        Route::delete("/TipoToma/log_delete/{id}", "destroy");
    });

    Route::controller(TipoTomaAplicableController::class)->group(function(){
        Route::post("/TipoTomaAplicable/store","store");
        Route::get("/TipoTomaAplicable/busquedaPorModelo","busquedaPorModelo");
        Route::get("/TipoTomaAplicable/busquedaPorTipoToma","busquedaPorTipoToma");
        Route::delete("/TipoTomaAplicable/destroyTipoTomaAplicable","destroyTipoTomaAplicable");
        Route::put("/TipoTomaAplicable/restaurarTipoTomaAplicable","restaurarTipoTomaAplicable");
    });
});