<?php

use App\Http\Controllers\Api\CargoController;
use App\Http\Controllers\Api\CargoDirectoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // cargos
    Route::controller(CargoController::class)->group(function () {
        Route::get("/cargos", "index");
        Route::post("/cargos/store", "store");
        Route::get("/cargos/show/{id}", "show");
        Route::get("/cargos/porModelo","cargosPorModelo");
        Route::get("/cargos/porModelo/pendientes","cargosPorModeloPendientes");
        Route::post("/cargo/generarDirecto","cargoDirecto");
    });
    // cargo directo
    Route::controller(CargoDirectoController::class)->group(function() {
        Route::get("/cargoDirecto","index");
        Route::post("/cargoDirecto/store","store");
        Route::get("/cargoDirecto/show/{id}" , "show");
        Route::put("/cargoDirecto/update/{id}" , "update");
        Route::delete("/cargoDirecto/delete/{id}", "destroy");
    });
});