<?php

use App\Http\Controllers\Api\FactibilidadController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    Route::controller(FactibilidadController::class)->group(function(){
        Route::get("/factibilidad" , "index");
        Route::get("/factibilidadContrato" , "contratoFactible");
        Route::post("/factibilidad/create" , "store");
        Route::get("/factibilidad/show/{id}" , "show");
        Route::put("/factibilidad/update/{id}" , "update");
        Route::delete("/factiblidad/delete/{id}" , "destroy");
        Route::put("/factibilidad/restaurar/{id}" , "restaurar");
    });
});