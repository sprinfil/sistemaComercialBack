<?php

use App\Http\Controllers\Api\SecuenciaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // secuencia
    Route::controller(SecuenciaController::class)->group(function() {
        Route::get("/secuencia","index");
        Route::post("/secuencia/crear","store");
    });
});