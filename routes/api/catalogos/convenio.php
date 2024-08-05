<?php

use App\Http\Controllers\Api\ConvenioController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // convenio catalogo
    Route::controller(ConvenioController::class)->group(function () {
        Route::get("/Convenio", "index");
        Route::post("/Convenio/create", "store");
        Route::put("/Convenio/update/{id}", "update");
        Route::put("/Convenio/restaurar/{id}", "update");

        //log delete significa borrado logico
        Route::delete("/Convenio/log_delete/{id}", "destroy");
        Route::put("/Convenio/restaurar/{id}", "restaurarDato");
    });
});