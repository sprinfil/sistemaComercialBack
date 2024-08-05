<?php

use App\Http\Controllers\Api\GiroComercialCatalogoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // giros comerciales
    Route::controller(GiroComercialCatalogoController::class)->group(function () {
        Route::get("/giros-catalogos", "index");
        Route::post("/giros-catalogos", "store");
        Route::get("/giros-catalogos/{id}", "show");
        Route::put("/giros-catalogos/{id}", "update");
        Route::delete("/giros-catalogos/{id}", "destroy");
        Route::put("/giros-catalogos/restaurar/{id}", "restaurarDato");
    });
});