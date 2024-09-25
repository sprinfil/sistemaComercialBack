<?php

use App\Http\Controllers\Api\ConceptoController;
use App\Http\Controllers\ConceptoAplicableController;
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

    //Concepto Aplicable Configuracion
    Route::controller(ConceptoAplicableController::class)->group(function(){
        Route::post("/ConceptoAplicable/Store","store");
        Route::get("/ConceptoAplicable/busquedaPorModelo","busquedaPorModelo");
        Route::get("/ConceptoAplicable/busquedaPorConcepto","busquedaPorConcepto");
        Route::put("/ConceptoAplicable/updateConceptoAplicable","updateConceptoAplicable");
        Route::delete("/ConceptoAplicable/destroyConceptoAplicable","destroyConceptoAplicable");
        Route::put("/ConceptoAplicable/restaurarConceptoAplicable","restaurarConceptoAplicable");
    });
});