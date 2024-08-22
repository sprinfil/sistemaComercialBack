<?php

use App\Http\Controllers\Api\DatoFiscalController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // Gestion de contribuyentes
    Route::controller(DatoFiscalController::class)->group(function () {
        Route::get("/datos_fiscales", "index");
        Route::post("/datos_fiscales/create", "store");
        Route::put("/datos_fiscales/update/{id}", "update");
        Route::delete("/datos_fiscales/delete/{id}", "destroy");
        Route::get("/datos_fiscales/show/{id}", "show");
        Route::get("/datos_fiscales/showPorModelo", "showPorModelo");
    });
});