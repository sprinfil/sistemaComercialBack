<?php

use Illuminate\Http\Request;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\OperadorController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // auth
    Route::post("/logout", [AuthController::class, "logout"]);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    //operadores
    Route::controller(OperadorController::class)->group(function () {
        Route::get("/Operador", "index");
        Route::post("/Operador/create", "store");
        
        Route::post("/Operador/create2", "store_2");

        Route::put("/Operador/update/{id}", "update");
        Route::put("/Operador/update2/{id_user}/{id_operador}", "update_2");

        Route::get("/Operador/show/{id}", "show");
        Route::delete("/Operador/log_delete/{id}", "destroy");
        Route::put("/Operador/restaurar/{id}", "restaurarOperador");
    });
    // users
    Route::controller(UserController::class)->group(function () {
        Route::get("/users/{id}", "show");
    }); 
});