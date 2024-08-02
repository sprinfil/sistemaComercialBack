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
use App\Http\Resources\OrdenTrabajoAccionResource;
use App\Http\Resources\OrdenTrabajoResource;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoAccion;
use App\Services\OrdenTrabajoCatalogoService;
use App\Services\OrdenTrabajoAccionService;
use App\Services\OrdenTrabajoService;
use Exception;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCatalogo()
    {
        return OrdenTrabajoCatalogoResource::collection(
            OrdenTrabajoCatalogo::with('OrdenTrabajoAccion')->get()
        );
       
    }
    public function indexConf()
    {
        return OrdenTrabajoAccionResource::collection(
            OrdenTrabajoAccion::all()
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
            DB::beginTransaction();
            $data=$request->validated();
            $dataConf=$data['orden_trabajo_accion'] ?? null;
            return $data;
            $orden=null;
            $catalogo=(new OrdenTrabajoCatalogoService())->store($data);
            
            if  ($dataConf){
                $dataConf['id_orden_trabajo_catalogo']=$catalogo['id'];
                $orden=(new OrdenTrabajoAccionService())->store($dataConf);
                $catalogo['orden_trabajo_acc']= $orden;
               
            }
            DB::commit();
            return response(new OrdenTrabajoCatalogoResource($catalogo),200);
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json([
                'message' => 'La orden de trabajo no se pudo registar.'
            ], 200);
        }
        

        /*
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
            $orden=(new OrdenTrabajoAccionService())->store($request->validated());
            if (!$orden){
                return response()->json([
                    'message'=>'Ya existe una configuración con las mismas caracteristicas para la orden de trabajo especificada'
                ],200);
            }
            else{
                return response(new OrdenTrabajoAccionResource($orden),200);
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
            $ordenTrabajo=OrdenTrabajoAccion::find($request->id);
            $Orden=OrdenTrabajoAccion::where('id_orden_trabajo_catalogo',$data['id_orden_trabajo_catalogo'])->
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
                return new OrdenTrabajoAccionResource($ordenTrabajo);
            }
           
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo modificar la orden de trabajo, introduzca datos correctos'], 200);
        }
    }
    
    public function destroyConf(OrdenTrabajoAccion $ordenTrabajo, Request $request)
    {
        try
        {
            $ordenTrabajo = OrdenTrabajoAccion::findOrFail($request["id"]);
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
            $ordenTrabajoConf=$ordenTrabajo->OrdenTrabajoAccion;
            if ($ordenTrabajoConf){
                return  OrdenTrabajoAccionResource::collection($ordenTrabajoConf);
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
        
        $data=(new OrdenTrabajoService())->crearOrden($request->validated());
        //$catalogo=OrdenTrabajo::create($data);
        //return response(new OrdenTrabajoResource($catalogo),200);
        return $data;
        
    }


    public function deleteOrden(StoreOrdenTrabajoRequest $request)
    {
        
        $data=(new OrdenTrabajoService())->crearOrden($request->validated());
        //$catalogo=OrdenTrabajo::create($data);
        //return response(new OrdenTrabajoResource($catalogo),200);
        return $data;
        
    }

    public function restoreOrden(StoreOrdenTrabajoRequest $request)
    {
        
        $data=(new OrdenTrabajoService())->crearOrden($request->validated());
        //$catalogo=OrdenTrabajo::create($data);
        //return response(new OrdenTrabajoResource($catalogo),200);
        return $data;
        
    }
}
