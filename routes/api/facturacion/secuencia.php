<?php

use App\Http\Controllers\Api\SecuenciaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // secuencia
    Route::controller(SecuenciaController::class)->group(function() {
        Route::get("/secuencia","index");
        Route::get("/secuencia/padre/show","secuenciasPadre");
        Route::get("/secuencia/personalizada/show","secuenciasOperador");
        Route::post("/secuencia/crear","store");
        Route::post("/secuencia/filtros","filtros");
        Route::delete("/secuencia/log_delete","delete");
    });
});