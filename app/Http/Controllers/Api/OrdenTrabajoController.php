<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrdenTrabajoCatalogoRequest;
use App\Http\Requests\StoreOrdenTrabajoConfRequest;
use App\Models\OrdenTrabajo;
use App\Http\Requests\StoreOrdenTrabajoRequest;
use App\Http\Requests\UpdateOrdenTrabajoCatalogoRequest;
use App\Http\Requests\UpdateOrdenTrabajoConfRequest;
use App\Http\Requests\UpdateOrdenTrabajoRequest;
use App\Http\Resources\OrdenTrabajoCatalogoResource;
use App\Http\Resources\OrdenTrabajoConfResource;
use App\Http\Resources\OrdenTrabajoResource;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use App\Services\OrdenTrabajoCatalogoService;
use App\Services\OrdenTrabajoConfService;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
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
        try{
            $catalogo=(new OrdenTrabajoCatalogoService())->store($request->validated());
            return response(new OrdenTrabajoCatalogoResource($catalogo),200);
        }
        catch(Exception $ex){
            return response()->json([
                'message' => 'La orden de trabajo no se pudo registar.',
                'restore' => false
            ], 200);
        }
       
        /*
        if ($catalogo[1]==true){
            if ($catalogo[0]->trashed()) {
                return response()->json([
                    'message' => 'El tipo de orden de trabajo y existe. ¿Desea restaurarlo?',
                    'restore' => true,
                    'orden_trabajo_catalogo_id' => $catalogo[0]->id
                ], 200);
            }
            else{
                return response()->json([
                    'message' => 'La orden de trabajo ya existe.',
                    'restore' => false
                ], 200);
            }
            
        }
        else{
           
        }
        */
       
        
    }

    /**
     * Display the specified resource.
     */
    public function showCatalogo(string $nombre)
    {
        try{
            $ordenTrabajo=OrdenTrabajoCatalogo::BuscarCatalogo($nombre);
            return OrdenTrabajoCatalogoResource::collection(
                $ordenTrabajo
            );
        }
        catch(Exception $ex){
            return response()->json(['error'=>'No se encontro una orden de trabajo con ese nombre']);
        }
       
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

    public function destroyCatalogo(OrdenTrabajoCatalogo $ordenTrabajo, Request $request)
    {
        try
        {
            (new OrdenTrabajoCatalogoService())->delete($request["id"]);
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (Exception $e) {

            return response()->json(['message' => 'error, No existe la orden que se desea borrar o ya se encuentra borrada'], 500);
        }
            
    }

    public function restoreCatalogo(OrdenTrabajoCatalogo $ordenTrabajo, Request $request)
    {

        $ordenTrabajo = OrdenTrabajoCatalogo::withTrashed()->findOrFail($request->id);

           // Verifica si el registro está eliminado
        if ($ordenTrabajo->trashed()) {
            // Restaura el registro
            $ordenTrabajo->restore();
            return response()->json(['message' => 'La OT ha sido restaurada.'], 200);
        }

    }

    //// CATALOGO CONF
    public function storeConf(StoreOrdenTrabajoConfRequest $request) //Ejemplo con service
    {
        try{
            $orden=(new OrdenTrabajoConfService())->store($request->validated());
            if (!$orden){
                return response()->json([
                    'message'=>'Ya existe una configuración con las mismas caracteristicas para la orden de trabajo especificada'
                ],200);
            }
            else{
                return response(new OrdenTrabajoConfResource($orden),200);
            } 
        }
        catch(Exception $ex){
            return response()->json([
                'error'=>'No se pudo crear la configuración para esta orden de trabajo'
            ],200);
        }
      
    }
    public function updateConf(UpdateOrdenTrabajoConfRequest $request, OrdenTrabajoCatalogo $ordenTrabajo)
    {
        try{
            $data=$request->validated();
            $ordenTrabajo=OrdenTrabajoConfiguracion::find($request->id);
            $Orden=OrdenTrabajoConfiguracion::where('id_orden_trabajo_catalogo',$data['id_orden_trabajo_catalogo'])->
            where('id_concepto_catalogo',$data['id_concepto_catalogo'])->
            where('accion',$data['accion'])->
            where('momento',$data['momento'])->get();
            
            if (count($Orden)>1){
                return response()->json([
                    'message'=>'Ya existe una configuración con las mismas caracteristicas para la orden de trabajo especificada'
                ],200);
            }
            else{
                $ordenTrabajo->update($data);
                $ordenTrabajo->save();
                return new OrdenTrabajoConfResource($ordenTrabajo);
            }
           
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo modificar la orden de trabajo, introduzca datos correctos'], 200);
        }
    }
    
    public function destroyConf(OrdenTrabajoConfiguracion $ordenTrabajo, Request $request)
    {
        try
        {
            $ordenTrabajo = OrdenTrabajoConfiguracion::findOrFail($request["id"]);
            $ordenTrabajo->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (Exception $e) {

            return response()->json(['message' => 'error'], 500);
        }
    }

    public function showConf(string $id)
    {
        try{
            $ordenTrabajo=OrdenTrabajoCatalogo::find($id);
            $ordenTrabajoConf=$ordenTrabajo->ordenTrabajoConfiguracion;
            if (count($ordenTrabajoConf)!=0){
                return OrdenTrabajoConfResource::collection($ordenTrabajoConf);
            }
            else{
                return response()->json(['message'=>'No se encontro una configuración para la orden de trabajo']);
            }

        }
        catch(Exception $ex){
            return response()->json(['error'=>'No se encontro una configuración para la orden de trabajo']);
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
