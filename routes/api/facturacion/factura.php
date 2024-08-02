<?php

use App\Http\Controllers\Api\FacturaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    Route::controller(FacturaController::class)->group(function(){
        Route::get("/factura","index");
        Route::post("/factura/create","store");
        Route::get("/factura/show/{id}","show");
        Route::get("/factura/facturaPorToma/{id}","facturaPorToma");
    });
});