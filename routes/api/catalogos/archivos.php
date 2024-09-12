<?php

use App\Http\Controllers\Api\ArchivoController;
use App\Http\Controllers\Api\TipoTomaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //Tipo Toma
    Route::controller(ArchivoController::class)->group(function () {
        Route::post("/archivo/create", "store");
        Route::get("/archivo/download/{filename}", "download");
    });
});