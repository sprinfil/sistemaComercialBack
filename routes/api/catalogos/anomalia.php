<?php

use App\Http\Controllers\Api\AnomaliaCatalagoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // anomalia catalogo
    Route::controller(AnomaliaCatalagoController::class)->group(function () {
        Route::get("/AnomaliasCatalogo", "index");
        Route::post("/AnomaliasCatalogo/create", "store");
        Route::put("/AnomaliasCatalogo/update/{id}", "update");
        Route::get("/AnomaliasCatalogo/show/{id}", "show");
        Route::delete("/AnomaliasCatalogo/log_delete/{id}", "destroy");
        Route::put("/AnomaliasCatalogo/restaurar/{id}", "restaurarDato");
    });
});