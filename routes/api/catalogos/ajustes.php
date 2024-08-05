<?php

use App\Http\Controllers\Api\AjusteCatalagoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // ajustes catalogo
    Route::controller(AjusteCatalagoController::class)->group(function () {
        Route::get("/AjustesCatalogo", "index");
        Route::post("/AjustesCatalogo/create", "store");
        Route::put("/AjustesCatalogo/update/{id}", "update");

        // log delete significa borrado logico
        Route::delete("/AjustesCatalogo/log_delete/{id}", "destroy");
        Route::put("/AjustesCatalogo/restaurar/{id}", "restaurarDato");
    });
});