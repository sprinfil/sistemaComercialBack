<?php

use App\Http\Controllers\Api\RutaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // rutas
    Route::controller(RutaController::class)->group(function() {
        Route::get("/ruta","index");
        Route::post("/ruta/create","store");
        Route::get("/ruta/show/{id}","show");
        Route::put("/ruta/update/{id}","update");
        Route::delete("/ruta/log_delete/{id}","destroy");
        Route::put("/ruta/restaurar/{id}","restaurarRuta");
        Route::post("/ruta/create_masive","masive_store");
        Route::post("/ruta/masive_polygon_delete","masive_polygon_delete");
        Route::post("/ruta/create_polygon","create_polygon");
    });
});