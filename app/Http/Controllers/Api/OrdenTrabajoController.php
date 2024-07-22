<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdenTrabajoCatalogoRequest;
use App\Http\Requests\StoreOrdenTrabajoConfRequest;
use App\Models\OrdenTrabajo;
use App\Http\Requests\StoreOrdenTrabajoRequest;
use App\Http\Requests\UpdateOrdenTrabajoCatalogoRequest;
use App\Http\Requests\UpdateOrdenTrabajoRequest;
use App\Http\Resources\OrdenTrabajoCatalogoResource;
use App\Http\Resources\OrdenTrabajoConfResource;
use App\Http\Resources\OrdenTrabajoResource;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use Exception;
use Illuminate\Http\Request;

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
    public function indexConf()
    {
        return OrdenTrabajoConfResource::collection(
            OrdenTrabajoConfiguracion::all()
        );
       
    }
    public function indexOrdenes()
    {
        return OrdenTrabajoResource::collection(
            OrdenTrabajo::all()
        );
       
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeCatalogo(StoreOrdenTrabajoCatalogoRequest $request)
    {
        $data=$request->validated();
        $catalogo=OrdenTrabajoCatalogo::create($data);
        return response(new OrdenTrabajoCatalogoResource($catalogo),200);
    }

    /**
     * Display the specified resource.
     */
    public function showCatalogo(OrdenTrabajo $ordenTrabajo, Request $request)
    {
        $data= $request->validated();
        $ordenTrabajo=OrdenTrabajoCatalogo::where('nombre','LIKE','%'.$data.'%')->get();
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateCatalogo(UpdateOrdenTrabajoCatalogoRequest $request, OrdenTrabajoCatalogo $ordenTrabajo)
    {
        try{
            $data=$request->validated();
            $ordenTrabajo=OrdenTrabajoCatalogo::find($request->id);
            $ordenTrabajo->update($data);
            $ordenTrabajo->save();
            return new OrdenTrabajoCatalogoResource($ordenTrabajo);
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo modificar la orden de trabajo, introduzca datos correctos'], 200);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyCatalogo(OrdenTrabajoCatalogo $ordenTrabajo, Request $request)
    {
        try
        {
            $ordenTrabajo = OrdenTrabajoCatalogo::findOrFail($request["id"]);
            $ordenTrabajo->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (Exception $e) {

            return response()->json(['message' => 'error'], 500);
        }
    }

    public function restoreCatalogo(OrdenTrabajoCatalogo $ordenTrabajo, Request $request)
    {

        $ordenTrabajo = OrdenTrabajoCatalogo::withTrashed()->findOrFail($request->id);

           // Verifica si el registro está eliminado
        if ($ordenTrabajo->trashed()) {
            // Restaura el registro
            $ordenTrabajo->restore();
            return response()->json(['message' => 'El usuario ha sido restaurado.'], 200);
        }

    }
    //// CATALOGO CONF
    public function storeConf(StoreOrdenTrabajoConfRequest $request)
    {
        $data=$request->validated();
        $catalogo=OrdenTrabajoConfiguracion::where('id_orden_trabajo_catalogo',$data['id_orden_trabajo_catalogo'])->
        where('id_concepto_catalogo',$data['id_concepto_catalogo'])->
        where('accion',$data['accion'])->
        where('momento',$data['momento'])->first();
        
        if ($catalogo){
            return response()->json([
                'message'=>'Ya existe una configuración con las mismas caracteristicas para la orden de trabajo especificada'
            ],200);
        }
        else{
            $catalogo=OrdenTrabajoConfiguracion::create($data);
            return response(new OrdenTrabajoConfResource($catalogo),200);
        }
            
        
        
        
    }
    //// ORDEN DE TRABAJO
    public function storeOrden(StoreOrdenTrabajoRequest $request)
    {
        /*
        $data=$request->validated();
        $catalogo=OrdenTrabajo::create($data);
        return response(new OrdenTrabajoResource($catalogo),200);
        */
    }
}
