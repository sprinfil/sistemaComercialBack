<?php

use App\Http\Controllers\Api\CfdiController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    Route::controller(CfdiController::class)->group(function () {
        Route::get("/cfdi" , "index");
        Route::post("/cfdi/create" , "store");
        Route::get("/cfdi/show/{id}" , "show");
        Route::put("/cfdi/update/{id}" , "update");
    });
});