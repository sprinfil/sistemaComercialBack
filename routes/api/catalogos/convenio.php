<?php

use App\Http\Controllers\Api\ConvenioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // Convenios
    Route::controller(ConvenioController::class)->group(function () {
        // Catalogo
        Route::get("/Convenio", "index");
        Route::post("/Convenio/create", "store");
        Route::put("/Convenio/update/{id}", "update");
        Route::delete("/Convenio/log_delete/{id}", "destroy");
        Route::put("/Convenio/restaurar/{id}", "restaurarDato");

        // Registro de convenio
        Route::post("/Convenio/BuscarConceptosConveniables","BuscarConceptosConveniables");
    });
});