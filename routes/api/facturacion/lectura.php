<?php

use App\Http\Controllers\Api\LecturaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // lectura
    Route::controller(LecturaController::class)->group(function() {
        Route::get("/lectura","index");
        Route::post("/lectura/store","store");
        Route::get("/lectura/show/{id}","show");
        Route::post("/lectura/import","import");
    });
});