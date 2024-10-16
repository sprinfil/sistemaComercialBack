<?php

use App\Http\Controllers\Api\ValidacionController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // solicitud de correcciones
    Route::controller(ValidacionController::class)->group(function(){
      Route::get("/validacion/periodo/{id}" , "anomaliasperiodo");
    });
});