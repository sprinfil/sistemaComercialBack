<?php

use App\Http\Controllers\Api\PagoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // pagos
    Route::controller(PagoController::class)->group(function () {
        Route::get("/pagos", "index");
        Route::post("/pagos/store", "store");
        Route::get("/pagos/show/{id}", "show");
        Route::get("/pagos/porModelo", "pagosPorModeloConDetalle");
        Route::get("/pagos/porModelo/totalPendiente", "totalPendiente");
        Route::get("/pagos/test/{id}", "test");
        Route::get("/pagos/detalle/{id}", "showDetalle");
    });
});