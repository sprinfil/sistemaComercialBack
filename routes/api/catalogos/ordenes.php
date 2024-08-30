<?php

use App\Http\Controllers\Api\OrdenTrabajoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // gestion de ordenes de trabajo
    Route::controller(OrdenTrabajoController::class)->group(function () {
        Route::get("/OrdenTrabajoCatalogo", "indexCatalogo");
        Route::put("/OrdenTrabajoCatalogo/create", "storeCatalogo");
        Route::put("/OrdenTrabajoCatalogo/update", "updateCatalogo");
        Route::put("/OrdenTrabajoCatalogo/create/cargos", "storeCargos");
        Route::put("/OrdenTrabajoCatalogo/create/encadenadas", "storeEncadenadas");
        Route::put("/OrdenTrabajoCatalogo/create/acciones", "storeAcciones");

        Route::put("/OrdenTrabajoCatalogo/restore/{id}", "restoreCatalogo");
        Route::delete("/OrdenTrabajoCatalogo/log_delete/{id}", "destroyCatalogo");
        Route::get("/OrdenTrabajoCatalogo/show/{id}", "showCatalogo");


        //ORDEN DE TRABAJO
        Route::post("/OrdenTrabajo", "indexOrdenes");
        Route::post("/OrdenTrabajo/create", "storeOrden");
        Route::put("/OrdenTrabajo/cerrar", "cerrarOrden");
        Route::put("/OrdenTrabajo/update", "asignarOrden");
        Route::post("/OrdenTrabajo/generar/masiva", "storeOrdenMasiva");
        Route::put("/OrdenTrabajo/asigna/masiva", "storeOrdenMasivaAsignacion");
        Route::put("/OrdenTrabajo/restore/{id}", "restoreOrden");
        Route::delete("/OrdenTrabajo/log_delete/{id}", "deleteOrden");
        Route::get("/OrdenTrabajo/show/{id}", "showOrden");
        Route::get("/OrdenTrabajo/filtros", "filtradoOrdenes");
    });
});