<?php

use App\Http\Controllers\Api\ConvenioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // Convenios
    Route::controller(ConvenioController::class)->group(function () {
        // Catalogo
        Route::get("/Convenio", "index");
        Route::post("/Convenio/create", "store");
        Route::put("/Convenio/update/{id}", "update");
        Route::delete("/Convenio/log_delete/{id}", "destroy");
        Route::put("/Convenio/restaurar/{id}", "restaurarDato");

        // Registro de convenio
        Route::post("/Convenio/BuscarConceptosConveniables","BuscarConceptosConveniables");
        Route::post("/Convenio/RegistrarConvenio","RegistrarConvenio");
        Route::put("/Convenio/CancelarConvenio", "CancelarConvenio");
        Route::get("/Convenio/ConsultarConvenio", "ConsultarConvenio");
        Route::get("/Convenio/ConsultarListaConvenio", "ConsultarListaConvenio");
        Route::get("/Convenio/ConsultarLetras", "ConsultarLetras");
        Route::get("/Convenio/buscarConveniosAplicablesTipoToma", "buscarConveniosAplicablesTipoToma");

        //Este metodo solo lo uso para probar se debe eliminar al final
        Route::get("/Convenio/crearCargoLetra", "crearCargoLetra");
        Route::put("/Convenio/pagoLetra", "pagoLetra");

    });
});