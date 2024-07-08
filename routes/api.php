<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\AjusteController;
use App\Http\Controllers\Api\AnomaliaController;
use App\Http\Controllers\Api\ConceptoController;
use App\Http\Controllers\Api\ConvenioController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\Dato_fiscalController;
use App\Http\Controllers\Api\AjusteCatalagoController;
use App\Http\Controllers\Api\AnomaliaCatalagoController;
use App\Http\Controllers\Api\DescuentoCatalogoController;
use App\Http\Controllers\Api\ConstanciaCatalogoController;
use App\Http\Controllers\Api\CatalogoBonificacionController;
use App\Http\Controllers\Api\DescuentoAsociadoController;
use App\Http\Controllers\Api\GiroComercialCatalogoController;
use App\Http\Controllers\Api\OperadorController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\Tipo_tomaController;

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
        Route::get("/AnomaliasCatalogo/show/{id}", "show");
        Route::delete("/AnomaliasCatalogo/log_delete/{id}", "destroy");
    });

    // Descuento catalogo
    Route::controller(DescuentoCatalogoController::class)->group(function () {
        Route::get("/descuentos-catalogos", "index");
        Route::post("/descuentos-catalogos", "store");
        Route::get("/descuentos-catalogos/{id}", "show");
        Route::put("/descuentos-catalogos/{id}", "update");
        Route::delete("/descuentos-catalogos/{id}", "destroy");
    });

    // Descuento asociado
    Route::controller(DescuentoAsociadoController::class)->group(function () {
        Route::get("/descuentos-asociado", "index");
        Route::post("/descuentos-asociado", "store");
        Route::get("/descuentos-asociado/{id}", "show");
        Route::put("/descuentos-asociado/{id}", "update");
        Route::delete("/descuentos-asociado/{id}", "destroy");
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

    //CONVENIOS
    Route::controller(ConvenioController::class)->group(function () {
        Route::get("/Convenio", "index");
        Route::post("/Convenio/create", "store");
        Route::put("/Convenio/update/{id}", "update");
        Route::put("/Concepto/restaurar/{id}", "update");

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
        //log delete significa borrado logico
        Route::put("/usuarios/log_delete/{id}", "destroy");
        Route::put("/usuarios/restore/{id}", "restaurarDato");
        //Consultas
        //nombres
        Route::get("/usuarios/consulta/{nombre}", "show");
        //CURP
        Route::get("/usuarios/consultaCURP/{curp}", "showCURP");
        //RFC
        Route::get("/usuarios/consultaRFC/{rfc}", "showRFC");
        //CORREO
        Route::get("/usuarios/consultaCorreo/{correo}", "showCorreo");
        //log delete significa borrado logico
        Route::delete("/usuarios/log_delete/{id}", "destroy");
    });

    // Gestion de contribuyentes
    Route::controller(Dato_fiscalController::class)->group(function () {
        Route::get("/Datos_fiscales", "index");
        Route::post("/Datos_fiscales/create", "store");
        Route::put("/Datos_fiscales/update/{id}", "update");
        Route::put("/Datos_fiscales/log_delete/{id}", "destroy");
        Route::get("/Datos_fiscales/show/{id}", "show");
    });
    //Tipo Toma
    Route::controller(Tipo_tomaController::class)->group(function () {
        Route::get("/TipoToma", "index");
        Route::post("/TipoToma/create", "store");
        Route::put("/TipoToma/update/{id}", "update");
        Route::get("/TipoToma/consulta/{nombre}", "show");
        Route::put("/TipoToma/restore/{id}", "restaurarDato");

        //log delete significa borrado logico
        Route::delete("/TipoToma/log_delete/{id}", "destroy");
    });

    // Servicios
    Route::controller(ServicioController::class)->group(function () {
        Route::get("/servicios", "index");
        Route::post("/servicios", "store");
        Route::get("/servicios/{id}", "show");
        Route::put("/servicios/{id}", "update");
        Route::delete("/servicios/{id}", "destroy");
    });

    //CONCEPTOS
    Route::controller(ConceptoController::class)->group(function () {
        Route::get("/Concepto", "index");
        Route::post("/Concepto/create", "store");
        Route::put("/Concepto/update/{id}", "update");
        Route::put("/Concepto/restaurar/{id}", "restaurarDato");
        //log delete significa borrado logico
        Route::put("/Concepto/log_delete/{id}", "destroy");
    });
    
    // Giros comerciales
    Route::controller(GiroComercialCatalogoController::class)->group(function () {
        Route::get("/giros-catalogos", "index");
        Route::post("/giros-catalogos", "store");
        Route::get("/giros-catalogos/{id}", "show");
        Route::put("/giros-catalogos/{id}", "update");
        Route::delete("/giros-catalogos/{id}", "destroy");
    });

    Route::controller(CatalogoBonificacionController::class)->group(function () {
        Route::get("/bonificacionesCatalogo", "index");
        Route::post("/bonificacionesCatalogo/create", "store");
        Route::put("/bonificacionesCatalogo/update/{id}", "update");
        Route::get("/bonificacionesCatalogo/show/{id}", "show");
        Route::put("/bonificacionesCatalogo/restaurar/{id}", "restaurarDato");
        //log delete significa borrado logico
        Route::delete("BonificacionesCatalogo/log_delete/{id}", "destroy");
    });

    Route::controller(OperadorController::class)->group(function () {
        Route::get("/Operador", "index");
        Route::post("/Operador/create", "store");
        Route::put("/Operador/update/{id}", "update");
        Route::get("/Operador/show/{id}", "show");
        Route::delete("/Operador/log_delete/{id}", "destroy");
        Route::put("/Operador/restaurar/{id}", "restaurarOperador");
    });
});
