<?php

use App\Http\Controllers\Api\CalleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // calle
    Route::controller(CalleController::class)->group(function() {
        Route::get("/calle","index");
        Route::get("/callesPorColonia/{id}","getCallesPorColonia");
        Route::post("/calle/store","store");
        Route::get("/calle/show/{id}" , "show");
        Route::put("/calle/update/{id}" , "update");
        Route::delete("/calle/delete/{id}", "destroy");
        Route::put("/calle/restore/{id}", "restaurarDato");
    });
});