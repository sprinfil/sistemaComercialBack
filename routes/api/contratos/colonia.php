<?php

use App\Http\Controllers\Api\ColoniaController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // colonia
    Route::controller(ColoniaController::class)->group(function() {
        Route::get("/colonia","index");
        Route::post("/colonia/store","store");
        Route::get("/colonia/show/{id}" , "show");
        Route::put("/colonia/update/{id}" , "update");
        Route::delete("/colonia/delete/{id}", "destroy");
        Route::put("/colonia/restore/{id}", "restaurarDato");
    });
});