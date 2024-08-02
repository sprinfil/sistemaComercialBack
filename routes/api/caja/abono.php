<?php

use App\Http\Controllers\Api\AbonoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // abonos
    Route::controller(AbonoController::class)->group(function () {
        Route::get("/abonos", "index");
        Route::post("/abonos/store", "store");
        Route::get("/abonos/show/{id}", "show");
        Route::put("/abonos/update/{id}", "update");
        Route::delete("/abonos/delete/{id}", "destroy");
    });
});