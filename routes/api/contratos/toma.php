<?php

use App\Http\Controllers\Api\FacturaController;
use App\Http\Controllers\Api\TomaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // toma
    Route::controller(TomaController::class)->group(function() {
        Route::post("/Toma/create","store");
        Route::get("/Toma","index");
        Route::put("/Toma/update/{id}","update");
        Route::delete("/Toma/log_delete/{id}","destroy");
        Route::get("/Toma/show/{id}","show");
        Route::get("/Toma/pagos/{id}","pagosPorToma");
        Route::get("/Toma/cargos/{id}","cargosPorToma");
        Route::get("/Toma/ordenesTrabajo/{id}","ordenesToma");
        Route::get("/Toma/general/{id}","general");
        Route::get("/Toma/codigo/{codigo}","buscarCodigoToma");
    });
});