<?php

use App\Http\Controllers\Api\LibroController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // libro
    Route::controller(LibroController::class)->group(function() {
        Route::get("/libro","index");
        Route::post("/libro/create","store");
        Route::get("/libro/show/{id}","show");
        Route::put("/libro/update/{id}","update");
        Route::delete("/libro/log_delete/{id}","destroy");
        Route::put("/libro/restaurar/{id}","restaurarLibro");
    });
});