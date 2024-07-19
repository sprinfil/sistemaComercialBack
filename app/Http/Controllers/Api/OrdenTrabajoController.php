<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdenTrabajoConfRequest;
use App\Models\OrdenTrabajo;
use App\Http\Requests\StoreOrdenTrabajoRequest;
use App\Http\Requests\UpdateOrdenTrabajoRequest;
use App\Http\Resources\OrdenTrabajoCatalogoResource;
use App\Http\Resources\OrdenTrabajoConfResource;
use App\Http\Resources\OrdenTrabajoResource;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCatalogo()
    {
        return OrdenTrabajoCatalogoResource::collection(
            OrdenTrabajoCatalogo::all()
        );
       
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeCatalogo(StoreOrdenTrabajoRequest $request)
    {
        $data=$request->validated();
        $catalogo=OrdenTrabajoCatalogo::create($data);
        return response(new OrdenTrabajoCatalogoResource($catalogo),200);
    }

    /**
     * Display the specified resource.
     */
    public function show(OrdenTrabajo $ordenTrabajo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateOrdenTrabajoRequest $request, OrdenTrabajo $ordenTrabajo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(OrdenTrabajo $ordenTrabajo)
    {
        //
    }

    //// CATALOGO CONF
    public function storeConf(StoreOrdenTrabajoConfRequest $request)
    {
        $data=$request->validated();
        $catalogo=OrdenTrabajoConfiguracion::create($data);
        return response(new OrdenTrabajoConfResource($catalogo),200);
    }
    //// ORDEN DE TRABAJO
    public function storeOrden(StoreOrdenTrabajoRequest $request)
    {
        $data=$request->validated();
        $catalogo=OrdenTrabajo::create($data);
        return response(new OrdenTrabajoResource($catalogo),200);
    }
}
