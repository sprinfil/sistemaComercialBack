<?php

use App\Http\Controllers\Api\DescuentoAsociadoController;
use App\Http\Controllers\Api\DescuentoCatalogoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // descuento catalogo
    Route::controller(DescuentoCatalogoController::class)->group(function () {
        Route::get("/descuentos-catalogos", "index");
        Route::post("/descuentos-catalogos", "store");
        Route::get("/descuentos-catalogos/{id}", "show");
        Route::put("/descuentos-catalogos/{id}", "update");
        Route::delete("/descuentos-catalogos/{id}", "destroy");
        Route::put("/descuentos-catalogos/restaurar/{id}", "restaurarDato");
    });
    // descuento asociado
    Route::controller(DescuentoAsociadoController::class)->group(function () {
        Route::get("/descuentos-asociado", "index");
        Route::post("/descuentos-asociado/store", "store");
        Route::get("/descuentos-asociado/{id}", "show");
        Route::get("/descuentos-asociado" , "ConsultarPorTomaUsuario");
        Route::put("/cancelar-descuentos-asociado/{id}", "CancelarDescuento");
        Route::put("/descuentos-asociado/{id}", "update");
        Route::delete("/descuentos-asociado/{id}", "destroy");
    });
});