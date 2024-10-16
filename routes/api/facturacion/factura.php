<?php

use App\Http\Controllers\Api\FacturaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    Route::controller(FacturaController::class)->group(function(){
        Route::get("/factura","index");
        Route::post("/factura/create","store");
        Route::post("/factura/create/toma/{id}","storeToma");
        Route::get("/factura/show/{id}","show");
        Route::post("/factura/toma/{id}","facturaPorToma");
        Route::post("/factura/create/periodo","storePeriodo");
    });
});