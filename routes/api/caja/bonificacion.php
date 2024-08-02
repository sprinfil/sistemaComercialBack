<?php

use App\Http\Controllers\Api\CatalogoBonificacionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //Bonificaciones
    Route::controller(CatalogoBonificacionController::class)->group(function () {
        Route::get("/bonificacionesCatalogo", "index");
        Route::post("/bonificacionesCatalogo/create", "store");
        Route::put("/bonificacionesCatalogo/update/{id}", "update");
        Route::get("/bonificacionesCatalogo/show/{id}", "show");
        Route::put("/bonificacionesCatalogo/restaurar/{id}", "restaurarDato");
        //log delete significa borrado logico
        Route::delete("BonificacionesCatalogo/log_delete/{id}", "destroy");
    });
});