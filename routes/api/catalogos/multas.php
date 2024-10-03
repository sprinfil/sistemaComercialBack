<?php

use App\Http\Controllers\Api\MultaCatalogoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //Catalogo de multas
    Route::controller(MultaCatalogoController::class)->group(function () {
        Route::get("/catalogomulta", "index");
        Route::post("/catalogomulta", "store");
        Route::get("/catalogomulta/{id}", "show");
        Route::put("/catalogomulta/{id}", "update");
        Route::delete("/catalogomulta/{id}", "destroy");
        Route::put("/catalogomulta/restaurar/{id}", "restaurar");
    });

});