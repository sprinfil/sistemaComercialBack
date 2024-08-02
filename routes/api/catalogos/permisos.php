<?php

use App\Http\Controllers\Api\ConceptoController;
use App\Http\Controllers\Api\RolController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // roles
    Route::controller(RolController::class)->group(function(){
        Route::get("/Rol", "index");
        Route::post("/Rol/create", "store");
        Route::put("/Rol/update/{id}", "update");

        Route::post("Rol/give_rol_permissions/{id}", "give_rol_permissions");
        Route::post("Rol/give_user_permissions/{id}", "give_user_permissions");

        Route::get("Rol/get_all_permissions_by_rol_id/{id}", "get_all_permissions_by_rol_id");
        Route::get("Rol/get_all_permissions_by_user_id/{id}", "get_all_permissions_by_user_id");
        Route::get("Rol/get_all_rol_names_by_user_id/{id}", "get_all_rol_names_by_user_id");

        //log delete significa borrado logico
        Route::delete("Rol/log_delete/{id}", "destroy");

        //ROLES A USUARIOS
        Route::post("Rol/assign_rol_to_user/{user_id}", "assign_rol_to_user");
    });
});