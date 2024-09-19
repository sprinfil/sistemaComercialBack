<?php

use App\Http\Controllers\Api\CajasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //cajas
    Route::controller(CajasController::class)->group(function () {
        Route::get("/cajas", "index");
        Route::post("/cajas/store", "store");
        Route::get("/cajas/test", "test");
        Route::put("/cajas/update", "update");
        Route::get("/cajas/pagos", "pagosPorCaja");
        Route::get("/cajas/cargos", "cargosPorCaja");
        // cancelaciones
        Route::post("/cajas/solicitudCancelacion", "solicitarCancelacionPago");
        Route::get("/cajas/solicitudesCancelacion", "solicitudesCancelacionPago");
        Route::put("/cajas/actualizarSolicitud", "actualizarSolicitudCancelacionPago");
        // sesiones
        Route::get("/cajas/buscarSesionCaja", "buscarSesionCaja");
        //AsignarOperadorCaja
        Route::post("/cajas/asignarOperador", "asignarOperador");
        Route::delete("/cajas/retirarAsignacion", "retirarAsignacion");
        //Catalogo Cajas
        Route::get("/cajas/consultarCajas", "consultarCajas");
        Route::post("/cajas/guardarCajaCatalogo", "guardarCajaCatalogo");
        Route::delete("/cajas/eliminarCajaCatalogo/{id}", "eliminarCajaCatalogo");
        Route::put("/cajas/restaurarCajaCatalogo/{id}", "restaurarCajaCatalogo");
        Route::put("/cajas/modificarCajaCatalogo/{id}", "modificarCajaCatalogo");
        Route::get("/cajas/mostrarCaja/{id}", "mostrarCaja");

        //Retiro Cajas
        Route::post("/cajas/retiro/registrarRetiro", "registrarRetiro");

        //ConsultaSesion
        Route::get("/cajas/estadoSesionCobro", "estadoSesionCobro");

        //
        Route::get("/cajas/sesionPrevia", "sesionPrevia"); //no estan cerradas
        Route::get("/cajas/cortesRechazados", "cortesRechazados"); //cortes rechazados
        Route::post("/cajas/cargarLetra/{id}", "cargarLetra"); // convenio
    });
});
