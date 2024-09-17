<?php

use App\Http\Controllers\Api\MedidorController;
use App\Http\Controllers\Api\TomaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // Medidores
    Route::controller(MedidorController::class)->group(function () {
        Route::get("/medidores", "index");
        Route::post("/medidores", "store");
        Route::get("/medidores/{id}", "show");
        Route::put("/medidores/{id}", "update");
        Route::delete("/medidores/{id}", "destroy");
    });

    Route::controller(TomaController::class)->group(function () {
        Route::get("/medidores/toma/{id}", "medidoresPorToma");
        Route::post("/medidor/nuevo", "registrarNuevoMedidor");
        Route::get("/medidor/activo/{id}", "medidorActivoPorToma");
    });
});