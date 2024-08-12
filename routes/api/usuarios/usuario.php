<?php

use App\Http\Controllers\Api\UsuarioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // usuario (morales y físicos)
    Route::controller(UsuarioController::class)->group(function () {
        Route::get("/usuarios", "index");
        Route::post("/usuarios/create", "store");
        Route::post("/usuarios/createmoral", "storemoral");
        Route::put("/usuarios/update/{id}", "update");
        Route::put("/usuarios/updateMoral/{id}", "updateMoral");
        // delete significa borrado logico
        Route::delete("/usuarios/log_delete/{id}", "destroy");
        Route::put("/usuarios/restore/{id}", "restaurarDato");//avisar
        // consultas
        // codigo
        Route::get("/usuarios/consultaCodigo/{codigo}", "showCodigo");
        // nombres
        Route::get("/usuarios/consulta/{nombre}", "show");
        // nombre contacto
        Route::get("/usuarios/consultaContacto/{nombre}", "showContacto");
        // usuario por Dirección de tomas
        Route::get("/usuarios/consultaDireccion/{direccion}", "showDireccion");
        // curp
        Route::get("/usuarios/consultaCURP/{curp}", "showCURP");
        // rfc
        Route::get("/usuarios/consultaRFC/{rfc}", "showRFC");
        // correo
        Route::get("/usuarios/consultaCorreo/{correo}", "showCorreo");
        // consulta general
        Route::get("/usuarios/consulta/general/{codigo}", "general");
        Route::get("/usuarios/consulta/tomas/{id}", "showTomas");
        // log delete significa borrado logico
        Route::delete("/usuarios/log_delete/{id}", "destroy");
        // datos fiscales del usuario
        Route::get("/usuarios/datos_fiscales/{id}", "datosFiscales");
        Route::post("/usuarios/datos_fiscales/storeOrUpdate/{id}", "storeOrUpdateDatosFiscales");
        //Consultar el saldo de un usuario
        Route::get("/usuarios/consultar/saldo/{id}" , "ConsultarSaldoDeUsuario");
    });
});