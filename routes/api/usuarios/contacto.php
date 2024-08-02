<?php

use App\Http\Controllers\Api\ContactoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // contacto
    Route::controller(ContactoController::class)->group(function(){
        Route::get("/contacto","index");
        Route::post("/contacto/create","store");
        Route::get("/contacto/show/{id}","show");
        Route::put("/contacto/update/{id}","update");
        Route::delete("/contacto/log_delete/{id}","destroy");
    });
});