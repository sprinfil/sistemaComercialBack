<?php

use App\Http\Controllers\Api\AbonoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ConceptoController;
use App\Http\Controllers\Api\ConvenioController;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AjusteCatalagoController;
use App\Http\Controllers\Api\AnomaliaCatalagoController;
use App\Http\Controllers\Api\AsignacionGeograficaController;
use App\Http\Controllers\Api\CajasController;
use App\Http\Controllers\Api\CalleController;
use App\Http\Controllers\Api\CargoController;
use App\Http\Controllers\Api\cargoDirectoController;
use App\Http\Controllers\Api\DescuentoCatalogoController;
use App\Http\Controllers\Api\ConstanciaCatalogoController;
use App\Http\Controllers\Api\CatalogoBonificacionController;
use App\Http\Controllers\Api\ColoniaController;
use App\Http\Controllers\Api\ContactoController;
use App\Http\Controllers\Api\factibilidadController;
use App\Http\Controllers\Api\DatosDomiciliacionController;
use App\Http\Controllers\Api\ContratoController;
use App\Http\Controllers\Api\correccionInformacionSolicitudController;
use App\Http\Controllers\Api\DatoFiscalController;
use App\Http\Controllers\Api\DescuentoAsociadoController;
use App\Http\Controllers\Api\FacturaController;
use App\Http\Controllers\Api\GiroComercialCatalogoController;
use App\Http\Controllers\Api\LibroController;
use App\Http\Controllers\Api\RolController;
use App\Http\Controllers\Api\MedidorController;
use App\Http\Controllers\Api\OperadorController;
use App\Http\Controllers\Api\OrdenTrabajoController;
use App\Http\Controllers\Api\RutaController;
use App\Http\Controllers\Api\ServicioController;
use App\Http\Controllers\Api\TarifaController;
use App\Http\Controllers\Api\Tipo_tomaController;
use App\Http\Controllers\Api\TomaController;

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
        Route::put("/Convenio/restaurar/{id}", "update");

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
        //codigo
        Route::get("/usuarios/consultaCodigo/{codigo}", "showCodigo");
        //nombres
        Route::get("/usuarios/consulta/{nombre}", "show");
        //CURP
        Route::get("/usuarios/consultaCURP/{curp}", "showCURP");
        //RFC
        Route::get("/usuarios/consultaRFC/{rfc}", "showRFC");
        //CORREO
        Route::get("/usuarios/consultaCorreo/{correo}", "showCorreo");
        //Consulta general
        Route::get("/usuarios/consulta/general/{codigo}", "general");
        Route::get("/usuarios/consulta/tomas/{id}", "showTomas");
        
        //log delete significa borrado logico
        Route::delete("/usuarios/log_delete/{id}", "destroy");
        //datos fiscales del usuario
        Route::get("/usuarios/datos_fiscales/{id}", "datosFiscales");
        Route::post("/usuarios/datos_fiscales/storeOrUpdate/{id}", "storeOrUpdateDatosFiscales");

    });
    // CONTRATOS
    Route::controller(ContratoController::class)->group(function () {
        Route::get("/contratos", "index");
        Route::post("/contratos/create", "store");
        Route::put("/contratos/update/{id}", "update");
        Route::put("/contratos/restore/{id}", "restaurarDato");
        Route::get("/contratos/consulta/{id}", "showPorToma");
        Route::get("/contratos/consultaFolio/{folio}/{ano?}", "showPorFolio");
        //log delete significa borrado logico
        Route::delete("/contratos/log_delete/{id}", "destroy");

        //Cotizaciones
        Route::prefix('contratos')->group(function (){
            Route::get("/cotizacion", "indexCotizacion");
            Route::get("/cotizacion/show", "showCotizacion");
            Route::post("/cotizacion/create", "crearCotizacion");
            Route::put("/cotizacion/update/{id}", "terminarCotizacion");
            Route::delete("/cotizacion/log_delete/{id}", "destroyCot");
            Route::put("/cotizacion/restore/{id}", "restaurarCot");

            Route::prefix('cotizacion')->group(function (){
                Route::get("/detalle", "indexCot");
                Route::get("/detalle/show", "showCotDetalle");
                Route::post("/detalle/create", "crearCotDetalle");
                Route::delete("/detalle/log_delete/{id}", "destroyCotDetalle");
                Route::put("/detalle/restore/{id}", "restaurarCotDetalle");
            });
            //Detalle de cotizacion 
        });
    });

    // Gestion de ordenes de trabajo
    Route::controller(OrdenTrabajoController::class)->group(function () {
        Route::get("/OrdenTrabajoCatalogo", "indexCatalogo");
        Route::post("/OrdenTrabajoCatalogo/create", "storeCatalogo");
        Route::put("/OrdenTrabajoCatalogo/update/{id}", "updateCatalogo");
        Route::put("/OrdenTrabajoCatalogo/restore/{id}", "restoreCatalogo");
        Route::delete("/OrdenTrabajoCatalogo/log_delete/{id}", "destroyCatalogo");
        Route::get("/OrdenTrabajoCatalogo/show/{nombre}", "showCatalogo");

        //ORDEN TRABAJO CONFIGURACIONES
        Route::get("/OrdenTrabajoConf", "indexConf");
        Route::post("/OrdenTrabajoConf/create", "storeConf");
        Route::put("/OrdenTrabajoConf/update/{id}", "updateConf");
        //Route::put("/OrdenTrabajoConf/restore/{id}", "restoreConf");
        Route::delete("/OrdenTrabajoConf/log_delete/{id}", "destroyConf");
        Route::post("/OrdenTrabajoConf/show/{nombre}", "showConf");

        //ORDEN DE TRABAJO
        Route::get("/OrdenTrabajo", "indexOrdenes");
        Route::post("/OrdenTrabajo/create", "storeOrden");
        Route::put("/OrdenTrabajo/update/{id}", "updateOrden");
        Route::put("/OrdenTrabajo/restore/{id}", "restoreOrden");
        Route::delete("/OrdenTrabajo/log_delete/{id}", "deleteOrden");
        Route::get("/OrdenTrabajo/show/{id}", "showOrden");
    });
    
    // Gestion de contribuyentes
    Route::controller(DatoFiscalController::class)->group(function () {
        Route::get("/datos_fiscales", "index");
        Route::post("/datos_fiscales/create", "store");
        Route::put("/datos_fiscales/update/{id}", "update");
        Route::delete("/datos_fiscales/delete/{id}", "destroy");
        Route::get("/datos_fiscales/show/{id}", "show");
        Route::get("/datos_fiscales/showPorModelo", "showPorModelo");
    });
    //Tipo Toma
    Route::controller(Tipo_tomaController::class)->group(function () {
        Route::get("/TipoToma", "index");
        Route::post("/TipoToma/create", "store");
        Route::put("/TipoToma/update/{id}", "update");
        Route::get("/TipoToma/consulta/{nombre}", "show");
        Route::put("/TipoToma/restore/{id}", "restaurarDato");
        Route::post("TipoToma/import","importarTipoTomaTarifas");
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

    // Auditoria
    Route::middleware(['audit'])->group(function () {
        // Cargos
        Route::controller(CargoController::class)->group(function () {
            Route::get("/cargos", "index");
            Route::post("/cargos/store/{id}", "store");
            Route::get("/cargos/show/{id}", "show");
        }); 
        // Abonos
        Route::controller(AbonoController::class)->group(function () {
            Route::get("/abonos", "index");
            Route::post("/abonos/store", "store");
            Route::get("/abonos/show/{id}", "show");
            Route::put("/abonos/update/{id}", "update");
            Route::delete("/abonos/delete/{id}", "destroy");
        });
    });

    // ROLES
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
        
        Route::post("/Operador/create2", "store_2");

        Route::put("/Operador/update/{id}", "update");
        Route::put("/Operador/update2/{id_user}/{id_operador}", "update_2");

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
        Route::get("/Toma/pagos/{id}","pagosPorToma");
        Route::get("/Toma/cargos/{id}","cargosPorToma");
        Route::get("/Toma/codigo/{codigo}","buscarCodigoToma");
    });
    //Solicitud de correcciones
    Route::controller(correccionInformacionSolicitudController::class)->group(function(){
        Route::post("/correccionInformacionSolicitud/create","store");
        Route::get("/correccionInformacionSolicitud","index");
        Route::get("/correccionInformacionSolicitud/show/{id}","show");
        Route::put("/correccionInformacionSolicitud/update/{id}","update");
        Route::delete("/correccionInformacionSolicitud/log_delete/{id}","destroy");

    });
    // Tarifa
    Route::controller(TarifaController::class)->group(function(){
        Route::post("/tarifa/create","store");
        Route::get("/tarifa","index");
        Route::get("/tarifa/show/{id}","show");
        Route::put("/tarifa/update/{id}","update");
        Route::put("/actualizar-tarifa","actualizarEstadoTarifa");
        Route::delete("/tarifa/log_delete/{id}","destroy");
        Route::put("tarifa/restaurar/{id}","restaurarTarifa");
    });

    // Cargo directo
    Route::controller(cargoDirectoController::class)->group(function() {
        Route::get("/cargoDirecto","index");
        Route::post("/cargoDirecto/store","store");
        Route::get("/cargoDirecto/show/{id}" , "show");
        Route::put("/cargoDirecto/update/{id}" , "update");
        Route::delete("/cargoDirecto/delete/{id}", "destroy");
    });

    //Tarifa concepto detalle
    Route::controller(TarifaController::class)->group(function(){
        Route::post("/tarifaConceptoDetalle/create","storeTarifaConceptoDetalle");
        Route::get("/tarifaConceptoDetalle","indexTarifaConceptoDetalle");
        Route::get("/tarifaConceptoDetalle/show/{id}","showTarifaConceptoDetalle");
        Route::put("/tarifaConceptoDetalle/update/{id}","updateTarifaConceptoDetalle");
        Route::get("/tarifaConceptoDetalle/conceptoAsociado/{id}","tarifaPorConceptoAsociado");

        //CONSULTAR CONCEPTOS POR TARIFA ID
        Route::get("/tarifaConceptoDetalle/{tarifa_id}","get_conceptos_detalles_by_tarifa_id");
        Route::get("/tarifaServicioDetalle/{tarifa_id}","get_servicios_detalles_by_tarifa_id");

    });

    //Tarifa Servicio detalle
    Route::controller(TarifaController::class)->group(function(){
        Route::post("/tarifaServicioDetalle/create","storeTarifaServicioDetalle");
        Route::get("/tarifaServicioDetalle/{tarifa_id}","get_servicios_detalles_by_tarifa_id");
        Route::get("/tarifaServicioDetalle/show/{id}","showTarifaServicioDetalle");
        Route::put("/tarifaServicioDetalle/update/{id}","updateTarifaServicioDetalle");
    });

    // Calle
    Route::controller(CalleController::class)->group(function() {
        Route::get("/calle","index");
        Route::get("/callesPorColonia/{id}","getCallesPorColonia");
        Route::post("/calle/store","store");
        Route::get("/calle/show/{id}" , "show");
        Route::put("/calle/update/{id}" , "update");
        Route::delete("/calle/delete/{id}", "destroy");
        Route::put("/calle/restore/{id}", "restaurarDato");
    });
  
     // Colonia
     Route::controller(ColoniaController::class)->group(function() {
        Route::get("/colonia","index");
        Route::post("/colonia/store","store");
        Route::get("/colonia/show/{id}" , "show");
        Route::put("/colonia/update/{id}" , "update");
        Route::delete("/colonia/delete/{id}", "destroy");
        Route::put("/colonia/restore/{id}", "restaurarDato");
    });
    //cajas
    Route::controller(CajasController::class)->group(function() {
        Route::get("/cajas","index");   
    });

    //Rutas
    Route::controller(RutaController::class)->group(function() {
        Route::get("/ruta","index");
        Route::post("/ruta/create","store");
        Route::get("/ruta/show/{id}","show");
        Route::put("/ruta/update/{id}","update");
        Route::delete("/ruta/log_delete/{id}","destroy");
        Route::put("/ruta/restaurar/{id}","restaurarRuta");
    });
    //Libro
    Route::controller(LibroController::class)->group(function() {
        Route::get("/libro","index");
        Route::post("/libro/create","store");
        Route::get("/libro/show/{id}","show");
        Route::put("/libro/update/{id}","update");
        Route::delete("/libro/log_delete/{id}","destroy");
        Route::put("/libro/restaurar/{id}","restaurarLibro");

    });

    //AsignacionGeografica
    Route::controller(AsignacionGeograficaController::class)->group(function(){
        Route::get("/asignacionGeografica","index");
        Route::post("/asignacionGeografica/create","store");
        Route::get('/asignacionGeografica/show/{id}','show');
        Route::put('/asignacionGeografica/update/{id}','update');
        Route::delete('/asignacionGeografica/log_delete/{id}','destroy');
        Route::get('/asignacionGeografica/Toma/{id}','asignaciongeograficaToma');
        Route::get('/asignacionGeografica/Libro/{id}','asignaciongeograficaLibro');
        Route::get('/asignacionGeografica/Ruta/{id}','asignaciongeograficaRuta');
    });

    Route::controller(ContactoController::class)->group(function(){
        Route::get("/contacto","index");
        Route::post("/contacto/create","store");
        Route::get("/contacto/show/{id}","show");
        Route::put("/contacto/update/{id}","update");
        Route::delete("/contacto/log_delete/{id}","destroy");

    });

    Route::controller(FacturaController::class)->group(function(){
        Route::get("/factura","index");
        Route::post("/factura/create","store");
        Route::get("/factura/show/{id}","show");
    });

});




