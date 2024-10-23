<?php

use App\Http\Controllers\Api\ValidacionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // solicitud de correcciones
    Route::controller(ValidacionController::class)->group(function(){
      Route::get("/validacion/periodo/{id}" , "consumosperiodo");
      Route::post("/validacion/consumo/registrarconsumo" , "registrarconsumo");
      Route::post("/validacion/consumo/modificarconsumo" , "modificarconsumo");
      Route::post("/validacion/consumo/modificarlectura/{id_periodo}" , "modificarlectura");
      Route::post("/valiacion/consumo/promediar" , "promediar");
    });
});