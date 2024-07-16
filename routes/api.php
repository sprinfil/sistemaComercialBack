<?php

use App\Http\Controllers\Api\AbonoController;
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
use App\Http\Controllers\Api\CargoController;
use App\Http\Controllers\Api\cargoDirectoController;
use App\Http\Controllers\Api\DescuentoCatalogoController;
use App\Http\Controllers\Api\ConstanciaCatalogoController;
use App\Http\Controllers\Api\CatalogoBonificacionController;
use App\Http\Controllers\Api\factibilidadController;
use App\Http\Controllers\Api\DatosDomiciliacionController;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\Api\correccionInformacionSolicitudController;
use App\Http\Controllers\Api\DescuentoAsociadoController;
use App\Http\Controllers\Api\GiroComercialCatalogoController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\MedidorController;
use App\Http\Controllers\Api\OperadorController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\Tipo_tomaController;
use App\Http\Controllers\Api\TomaController;
use App\Models\correccionInformacionSolicitud;

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
        Route::put("/AnomaliasCatalogo/restaurar/{id}", "restaurarDato");
    });

    // Descuento catalogo
    Route::controller(DescuentoCatalogoController::class)->group(function () {
        Route::get("/descuentos-catalogos", "index");
        Route::post("/descuentos-catalogos", "store");
        Route::get("/descuentos-catalogos/{id}", "show");
        Route::put("/descuentos-catalogos/{id}", "update");
        Route::delete("/descuentos-catalogos/{id}", "destroy");
        Route::put("/descuentos-catalogos/restaurar/{id}", "restaurarDato");
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
        Route::delete("/AjustesCatalogo/log_delete/{id}", "destroy");
        Route::put("/AjustesCatalogo/restaurar/{id}", "restaurarDato");
    });

    //CONVENIOS
    Route::controller(ConvenioController::class)->group(function () {
        Route::get("/Convenio", "index");
        Route::post("/Convenio/create", "store");
        Route::put("/Convenio/update/{id}", "update");
        Route::put("/Concepto/restaurar/{id}", "update");

        //log delete significa borrado logico
        Route::delete("/Convenio/log_delete/{id}", "destroy");
        Route::put("/Convenio/restaurar/{id}", "restaurarDato");
    });

    //Constancia
    Route::controller(ConstanciaCatalogoController::class)->group(function () {
        Route::get("/ConstanciasCatalogo", "index");
        Route::post("/ConstanciasCatalogo/create", "store");
        Route::put("/ConstanciasCatalogo/update/{id}", "update");

        //log delete significa borrado logico
        Route::delete("/ConstanciasCatalogo/log_delete/{id}", "destroy");
        Route::put("/ConstanciasCatalogo/restaurar/{id}", "restaurarDato");
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
    // CONTRATOS 
    Route::controller(ContratoController::class)->group(function () {
        Route::get("/contratos", "index");
        Route::post("/contratos/create", "store");
        Route::put("/contratos/update/{id}", "update");
        Route::put("/contratos/restore/{id}", "restaurarDato");
        Route::get("/contratos/consulta/{nombre}", "showPorUsuario");
        Route::get("/contratos/consultaFolio/{folio}", "showPorFolio");
        //log delete significa borrado logico
        Route::delete("/contratos/log_delete/{id}", "destroy");
        Route::prefix('contratos')->group(function (){
            Route::get("/cotizacion", "indexCotizacion");
            Route::post("/cotizacion/create/{id}", "crearCotizacion");
        });
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

    // Medidores
    Route::controller(MedidorController::class)->group(function () {
        Route::get("/medidores", "index");
        Route::post("/medidores", "store");
        Route::get("/medidores/{id}", "show");
        Route::put("/medidores/{id}", "update");
        Route::delete("/medidores/{id}", "destroy");
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
        Route::put("/giros-catalogos/restaurar/{id}", "restaurarDato");
    });

    // Datos domiciliados
    Route::controller(DatosDomiciliacionController::class)->group(function () {
        Route::get("/datos-domiciliados", "index");
        Route::post("/datos-domiciliados", "store");
        Route::get("/datos-domiciliados/{id}", "show");
        Route::put("/datos-domiciliados/{id}", "update");
        Route::delete("/datos-domiciliados/{id}", "destroy");
    });

     // Cargos
     Route::controller(CargoController::class)->group(function () {
        Route::get("/cargos", "index");
        Route::post("/cargos", "store");
        Route::get("/cargos/{id}", "show");
        Route::put("/cargos/{id}", "update");
        Route::delete("/cargos/{id}", "destroy");
    });

    // Abonos
    Route::controller(AbonoController::class)->group(function () {
        Route::get("/abonos", "index");
        Route::post("/abonos", "store");
        Route::get("/abonos/{id}", "show");
        Route::put("/abonos/{id}", "update");
        Route::delete("/abonos/{id}", "destroy");
    });

    // ROLES
    Route::controller(RolController::class)->group(function(){
        Route::get("/Rol", "index");
        Route::post("/Rol/create", "store");
        Route::put("/Rol/update/{id}", "update");

        Route::post("Rol/give_rol_permissions/{id}", "give_rol_permissions");
        Route::get("Rol/get_all_permissions_by_rol_id/{id}", "get_all_permissions_by_rol_id");

        //log delete significa borrado logico
        Route::delete("Rol/log_delete/{id}", "destroy");
    });

    Route::controller(factibilidadController::class)->group(function(){
        Route::get("/factibilidad" , "index");
        Route::get("/factibilidadContrato" , "contratoFactible");
        Route::post("/factibilidad/create" , "store");
        Route::get("/factibilidad/show/{id}" , "show");
        Route::put("/factibilidad/update/{id}" , "update");
        Route::delete("/factiblidad/delete/{id}" , "destroy");
        Route::put("/factibilidad/restaurar/{id}" , "restaurar");
    });
    
    //BONIFICACIONES
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
    //Toma
    Route::controller(TomaController::class)->group(function() {
        Route::post("/Toma/create","store");
        Route::get("/Toma","index");
        Route::put("/Toma/update/{id}","update");
        Route::delete("/Toma/log_delete/{id}","destroy");
        Route::get("/Toma/show/{id}","show");
    });
    //Solicitud de correcciones
    Route::controller(correccionInformacionSolicitudController::class)->group(function(){
        Route::post("/correccionInformacionSolicitud/create","store");
        Route::get("/correccionInformacionSolicitud","index");
        Route::get("/correccionInformacionSolicitud/show/{id}","show");
        Route::put("/correccionInformacionSolicitud/update/{id}","update");
        Route::delete("/correccionInformacionSolicitud/log_delete/{id}","destroy");
        
    });
    // Cargo directo
    Route::controller(cargoDirectoController::class)->group(function() {
        Route::get("/cargoDirecto","index");
        Route::post("/cargoDirecto/store","store");
        Route::get("/cargoDirecto/show/{id}" , "show");
        Route::put("/cargoDirecto/update/{id}" , "update");
        Route::delete("/cargoDirecto/delete/{id}", "destroy");
    });
});



