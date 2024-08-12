<?php

use App\Http\Controllers\Api\OrdenTrabajoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'audit'])->group(function () {
    // gestion de ordenes de trabajo
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
        Route::put("/OrdenTrabajo/cerrar", "cerrarOrden");
        Route::put("/OrdenTrabajo/update", "asignarOrden");
        Route::put("/OrdenTrabajo/restore/{id}", "restoreOrden");
        Route::delete("/OrdenTrabajo/log_delete/{id}", "deleteOrden");
        Route::get("/OrdenTrabajo/show/{id}", "showOrden");
    });
});