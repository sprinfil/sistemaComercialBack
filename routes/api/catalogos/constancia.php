<?php

use App\Http\Controllers\Api\ConstanciaCatalogoController;
use App\Http\Controllers\Api\ConstanciaController;
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

    Route::controller(ConstanciaController::class)->group(function (){
        Route::post("/Constancia/store","store");
        Route::put("/Constancia/pagoConstancia","pagoConstancia");// solo para pruebas
        Route::get("/Constancia/buscarRegistroConstancia","buscarRegistroConstancia");
        Route::get("/Constancia/EntregarConstancia","EntregarConstancia");
    });
});