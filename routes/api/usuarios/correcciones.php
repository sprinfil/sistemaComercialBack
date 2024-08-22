<?php

use App\Http\Controllers\Api\CorreccionSolicitudController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // solicitud de correcciones
    Route::controller(CorreccionSolicitudController::class)->group(function(){
        Route::post("/correccionInformacionSolicitud/create","store");
        Route::get("/correccionInformacionSolicitud","index");
        Route::get("/correccionInformacionSolicitud/show/{id}","show");
        Route::put("/correccionInformacionSolicitud/update/{id}","update");
        Route::delete("/correccionInformacionSolicitud/log_delete/{id}","destroy");
    });
});