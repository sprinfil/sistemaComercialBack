<?php

use App\Http\Controllers\Api\AsignacionGeograficaController;
use App\Http\Controllers\Api\CalleController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // asignacion geografica
    Route::controller(AsignacionGeograficaController::class)->group(function(){
        Route::get("/asignacionGeografica","index");
        Route::post("/asignacionGeografica/create","store");
        Route::get('/asignacionGeografica/show/{id}','show');
        Route::put('/asignacionGeografica/update/{id}','update');
        Route::delete('/asignacionGeografica/log_delete/{id}','destroy');
        Route::get('/asignacionGeografica/Toma/{id}','asignaciongeograficaToma');
        Route::get('/asignacionGeografica/Libro/{id}','asignaciongeograficaLibro');
        Route::get('/asignacionGeografica/Ruta/{id}','asignaciongeograficaRuta');
        Route::post('/asignacionGeografica/update_points/{asignacionGeografica_id}','update_points_with_asignacion_geografica_id');
    });
});