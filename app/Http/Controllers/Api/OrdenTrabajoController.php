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
use App\Http\Resources\OrdenesTrabajoCargoResource;
use App\Http\Resources\OrdenesTrabajoEncadenadaResource;
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
use PhpParser\Node\Stmt\Return_;

class OrdenTrabajoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function indexCatalogo()
    {
        return OrdenTrabajoCatalogoResource::collection(
            OrdenTrabajoCatalogo::with('ordenTrabajoAccion','ordenTrabajoCargos','ordenTrabajoEncadenado')->get()
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
            $catalogo=(new OrdenTrabajoCatalogoService())->store($data);
            if ($catalogo=="Existe"){
                return response()->json(["message"=>"Ya existe una OT con este nombre",201]);
            }
            DB::commit();
            return response(["Orden_Trabajo_Catalogo"=>$catalogo],200);
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json([
                'message' => 'La orden de trabajo no se pudo registar/actualizar.'
            ], 200);
        }
            
        
    }
    public function storeAcciones(StoreOrdenTrabajoCatalogoRequest $request){
        DB::beginTransaction();
        $data=$request->validated();
        $acciones=(new OrdenTrabajoAccionService())->store($data);
        DB::commit();
        return response(["Orden_Trabajo_Acciones"=>$acciones],200);
        try{
           
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json([
                'message' => 'Las acciones de la OT no se pudieron registar/actualizar.'
            ], 200);
        }
       
    }
    public function storeCargos(StoreOrdenTrabajoCatalogoRequest $request){
        try{
            DB::beginTransaction();
            $data=$request->validated();
            $cargos=(new OrdenTrabajoCatalogoService())->storeCargos($data);
            DB::commit();
            return response(["Orden_Trabajo_Cargos"=>$cargos],200);
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json([
                'message' => 'Los cargos de la OT no se pudieron registar/actualizar.'
            ], 200);
        }
 
    }
    public function storeEncadenadas(StoreOrdenTrabajoCatalogoRequest $request){
        try{
            DB::beginTransaction();
            $data=$request->validated();
            $encadenadas=(new OrdenTrabajoCatalogoService())->storeOTEncadenadas($data);
            DB::commit();
            return response(["Orden_Trabajo_Encadenadas"=>$encadenadas],200);
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json([
                'message' => 'No se pudieron encadenar las OT.'
            ], 200);
        }

    }
    /**
     * Display the specified resource.
     */
    public function showCatalogo(string $id)
    {
        try{
            $ordenTrabajo=OrdenTrabajoCatalogo::find($id);
            $ordenTrabajo->ordenTrabajoAccion;
            $ordenTrabajo->ordenTrabajoCargos;
            $ordenTrabajo->ordenTrabajoEncadenado;
            return new OrdenTrabajoCatalogoResource(
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
        $data=$request->validated();
        $catalogo=(new OrdenTrabajoCatalogoService())->store($data);
    }

    public function destroyCatalogo(OrdenTrabajoCatalogo $ordenTrabajo, Request $request)
    {
        try
        {
            $OT=(new OrdenTrabajoCatalogoService())->delete($request["id"]);
            if  ($OT=="No valido"){
                return response()->json(['message' => 'No se puede elimninar el tipo de OT. Existen Ordenes de trabajo vigentes ya vinculadas'], 200);
            }
            else{
                return response()->json(['message' => 'Eliminado correctamente'], 200);
            }
            
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

    /*
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
        */



    //// ORDEN DE TRABAJO
    public function storeOrden(StoreOrdenTrabajoRequest $request)
    {
       
       try{
        DB::beginTransaction();
        $data=(new OrdenTrabajoService())->crearOrden($request->validated());
        if (!$data){
            return response()->json(["message"=>"Ya existe una OT vigente, por favor concluyala primero antes de generar otra"],202);
        }
        else
        {
            DB::commit();
            return response(new OrdenTrabajoResource($data),200);
        }
       }
       catch(Exception $ex){
        DB::rollBack();
        return response()->json(["error"=>"No se pudo generar la Orden de trabajo"],202);
       }
   
        
        
    }
    public function asignarOrden(UpdateOrdenTrabajoRequest $request)
    {
        DB::beginTransaction();
        
        $datos=$request->validated();
        $datos['id']=$request->id;
        $data=(new OrdenTrabajoService())->asignar($datos);
        if (!$data){
            return response()->json(["message"=>"Ya existe una OT vigente, por favor concluyala primero antes de generar otra"],202);
        }
        else
        {
            DB::commit();
            return response(new OrdenTrabajoResource($data),200);
            //return "caca";
        }
       try{
        
       }
       catch(Exception $ex){
        DB::rollBack();
       }
   
        
        
    }
    public function cerrarOrden(Request $request)
    {
       
       try{
        
        DB::beginTransaction();
        $data=$request->all();
        $OT=$data['orden_trabajo'];
        $modelos=$data['modelos'];
        
        $Acciones=(new OrdenTrabajoService())->concluir($OT,$modelos);
        if (!$Acciones){
            return response()->json(["message"=>"la OT especificada ya se cerro"]);
            DB::rollBack();
        }
        else{
            DB::commit();
            return $Acciones;
        }
       }
       catch(Exception $ex){
        return response()->json(["error"=>"Ha ocurrido un error al cerrar la orden de trabajo ".$ex->getMessage()]);
       }
       
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
