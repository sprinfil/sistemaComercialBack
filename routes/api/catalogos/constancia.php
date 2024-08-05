<?php

use App\Http\Controllers\Api\ConstanciaCatalogoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // constancia catalogo
    Route::controller(ConstanciaCatalogoController::class)->group(function () {
        Route::get("/ConstanciasCatalogo", "index");
        Route::post("/ConstanciasCatalogo/create", "store");
        Route::put("/ConstanciasCatalogo/update/{id}", "update");

        //log delete significa borrado logico
        Route::delete("/ConstanciasCatalogo/log_delete/{id}", "destroy");
        Route::put("/ConstanciasCatalogo/restaurar/{id}", "restaurarDato");
    });
});