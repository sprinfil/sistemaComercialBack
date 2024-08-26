<?php

use App\Http\Controllers\Api\CajasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //cajas
    Route::controller(CajasController::class)->group(function() {
        Route::get("/cajas","index");  
        Route::post("/cajas/store","store");
        Route::get("/cajas/test","test");  
        Route::put("/cajas/update","update");
        Route::get("/cajas/buscarSesionCaja","buscarSesionCaja");

        //AsignarOperadorCaja
        Route::post("/cajas/asignarOperador","asignarOperador");
        Route::delete("/cajas/retirarAsignacion","retirarAsignacion");

        //Catalogo Cajas
        Route::get("/cajas/consultarCajas","consultarCajas");
        Route::post("/cajas/guardarCajaCatalogo","guardarCajaCatalogo");
        Route::delete("/cajas/eliminarCajaCatalogo/{id}","eliminarCajaCatalogo");
        Route::put("/cajas/restaurarCajaCatalogo/{id}","restaurarCajaCatalogo");
        Route::put("/cajas/modificarCajaCatalogo/{id}","modificarCajaCatalogo");
        Route::get("/caja/mostrarCaja/{id}","mostrarCaja");
    });
});