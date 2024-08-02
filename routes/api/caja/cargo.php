<?php

use App\Http\Controllers\Api\CargoController;
use App\Http\Controllers\Api\cargoDirectoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // cargos
    Route::controller(CargoController::class)->group(function () {
        Route::get("/cargos", "index");
        Route::post("/cargos/store/{id}", "store");
        Route::get("/cargos/show/{id}", "show");
    });
    // cargo directo
    Route::controller(cargoDirectoController::class)->group(function() {
        Route::get("/cargoDirecto","index");
        Route::post("/cargoDirecto/store","store");
        Route::get("/cargoDirecto/show/{id}" , "show");
        Route::put("/cargoDirecto/update/{id}" , "update");
        Route::delete("/cargoDirecto/delete/{id}", "destroy");
    });
});