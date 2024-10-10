<?php

use App\Http\Controllers\Api\MultaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //Catalogo de multas
    Route::controller(MultaController::class)->group(function () {
        Route::get("/multa", "index");
        Route::post("/multa/store", "store");
        Route::get("/multa/consultarmultas" , "consultar");
        Route::get("/multa/{id}", "show");
        Route::put("/multa/update/{id}", "update");
        Route::delete("/multa/delete/{id}", "destroy");
        Route::put("/multa/restaurar/{id}", "restaurar");
        Route::get("/multa/imprimeMulta/{id}", "generarFormatoMulta");
    });
    
});