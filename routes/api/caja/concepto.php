<?php

use App\Http\Controllers\Api\ConceptoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //CONCEPTOS
    Route::controller(ConceptoController::class)->group(function () {
        Route::get("/Concepto", "index");
        Route::get("/Concepto/cargable", "conceptosCargables");
        Route::post("/Concepto/create", "store");
        Route::put("/Concepto/update/{id}", "update");
        Route::put("/Concepto/restaurar/{id}", "restaurarDato");
        //log delete significa borrado logico
        Route::put("/Concepto/log_delete/{id}", "destroy");
    });
});