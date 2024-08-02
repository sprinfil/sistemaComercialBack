<?php

use App\Http\Controllers\Api\Tipo_tomaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //Tipo Toma
    Route::controller(Tipo_tomaController::class)->group(function () {
        Route::get("/TipoToma", "index");
        Route::post("/TipoToma/create", "store");
        Route::put("/TipoToma/update/{id}", "update");
        Route::get("/TipoToma/consulta/{nombre}", "show");
        Route::put("/TipoToma/restore/{id}", "restaurarDato");
        Route::post("TipoToma/import","importarTipoTomaTarifas");
        //log delete significa borrado logico
        Route::delete("/TipoToma/log_delete/{id}", "destroy");
    });
});