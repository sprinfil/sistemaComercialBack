<?php

use App\Http\Controllers\Api\ConsumoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    Route::controller(ConsumoController::class)->group(function(){
        Route::get("/consumo","index");
        Route::post("/consumo/create","store");
        Route::get("/consumo/show/{id}","show");
    });
});