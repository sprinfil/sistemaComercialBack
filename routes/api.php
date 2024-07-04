<?php

use App\Http\Controllers\Api\AjusteCatalagoController;
use App\Http\Controllers\Api\AnomaliaCatalagoController;
use App\Http\Controllers\Api\AjusteController;
use App\Http\Controllers\Api\AnomaliaController;
use App\Http\Controllers\Api\DescuentoCatalogoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ConstanciaCatalogoController;
use App\Http\Controllers\Api\GiroComercialCatalogoController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ConceptoController;
use App\Http\Controllers\Api\ConvenioController;
use App\Http\Controllers\Api\UsuarioController;

//Route::post('/signup',[AuthController::class, "signup"]);
Route::post('/login', [AuthController::class, "login"]);


Route::middleware('auth:sanctum')->group(function () {
    //AQUI VAN TODAS LAS RUTAS
    Route::post("/logout", [AuthController::class, "logout"]);

    //ANOMALIAS
    Route::controller(AnomaliaCatalagoController::class)->group(function () {
        Route::get("/AnomaliasCatalogo", "index");
        Route::post("/AnomaliasCatalogo/create", "store");
        Route::put("/AnomaliasCatalogo/update/{id}", "update");
        Route::put("/AnomaliasCatalogo/log_delete/{id}", "destroy");
    });

    //DESCUENTOS
    Route::controller(DescuentoCatalogoController::class)->group(function () {
        Route::get("/descuentos", "index");
        Route::post("/descuentos/create", "store");
        Route::put("/descuentos/update/{id}", "update");

        //log delete significa borrado logico
        Route::put("/descuentos/log_delete/{id}", "destroy");
    });

    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    //USERS
    Route::controller(UserController::class)->group(function () {
        Route::get("/users/{id}", "show");
    });

    //AJUSTES
    Route::controller(AjusteCatalagoController::class)->group(function () {
        Route::get("/AjustesCatalogo", "index");
        Route::post("/AjustesCatalogo/create", "store");
        Route::put("/AjustesCatalogo/update/{id}", "update");

        //log delete significa borrado logico
        Route::put("/AjustesCatalogo/log_delete/{id}", "destroy");
    });

    //CONCEPTOS
    Route::controller(ConceptoController::class)->group(function () {
        Route::get("/Concepto", "index");
        Route::post("/Concepto/create", "store");
        Route::put("/Concepto/update/{id}", "update");

        //log delete significa borrado logico
        Route::put("/Concepto/log_delete/{id}", "destroy");
    });

    //CONVENIOS
    Route::controller(ConvenioController::class)->group(function () {
        Route::get("/Convenio", "index");
        Route::post("/Convenio/create", "store");
        Route::put("/Convenio/update/{id}", "update");

        //log delete significa borrado logico
        Route::put("/Convenio/log_delete/{id}", "destroy");
    });

    //Constancia
    Route::controller(ConstanciaCatalogoController::class)->group(function () {
        Route::get("/ConstanciasCatalogo", "index");
        Route::post("/ConstanciasCatalogo/create", "store");
        Route::put("/ConstanciasCatalogo/update/{id}", "update");

        //log delete significa borrado logico
        Route::put("/ConstanciasCatalogo/log_delete/{id}", "destroy");
    });
    // USUARIOS (morales y físicos)
Route::controller(UsuarioController::class)->group(function () {
    Route::get("/usuarios", "index");
    Route::post("/usuarios/create", "store");
    Route::post("/usuarios/createmoral", "storemoral");
    Route::put("/usuarios/update/{id}", "update");
    Route::put("/usuarios/updateMoral/{id}", "updateMoral");
    Route::get("/usuarios/consulta/{nombre}", "show");

    
    //log delete significa borrado logico
    Route::put("/usuarios/log_delete/{id}", "destroy");
});

    // Giros comerciales
    Route::controller(GiroComercialCatalogoController::class)->group(function () {
        Route::get("/giros-catalogos", "index");
        Route::post("/giros-catalogos", "store");
        Route::get("/giros-catalogos/{id}", "show");
        Route::put("/giros-catalogos/{id}", "update");
        Route::delete("/giros-catalogos/{id}", "destroy");
    });
});
