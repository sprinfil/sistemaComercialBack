<?php

use App\Http\Controllers\Api\PeriodoController;
use App\Http\Controllers\Api\TarifaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // tarifa
    Route::controller(PeriodoController::class)->group(function () {
        Route::get("/periodos", "index");
    });
});
