<?php

use App\Http\Controllers\Api\DatosDomiciliacionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // datos domiciliados
    Route::controller(DatosDomiciliacionController::class)->group(function () {
        Route::get("/datos-domiciliados", "index");
        Route::post("/datos-domiciliados", "store");
        Route::get("/datos-domiciliados/{id}", "show");
        Route::put("/datos-domiciliados/{id}", "update");
        Route::delete("/datos-domiciliados/{id}", "destroy");
    });
});