<?php

use App\Http\Controllers\Api\CajasController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    //cajas
    Route::controller(CajasController::class)->group(function() {
        Route::get("/cajas","index");  
        Route::post("/cajas/store","store");
        Route::get("/cajas/test","test");  
        Route::put("/cajas/update","update");
    });
});