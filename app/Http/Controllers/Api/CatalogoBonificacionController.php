<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatalogoBonificacion;
use App\Http\Requests\StoreCatalogoBonificacionRequest;
use App\Http\Requests\UpdateCatalogoBonificacionRequest;
use App\Http\Resources\CatalogoBonificacionResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CatalogoBonificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return CatalogoBonificacionResource::collection(
        CatalogoBonificacion::all()
       );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CatalogoBonificacion $catalogoBonificacion , StoreCatalogoBonificacionRequest $request)
    {
        //Se valida el store
        $data = $request->validated();
        //Busca por nombre los eliminados
        $catalogoBonificacion = CatalogoBonificacion::withoutTrashed()->where('nombre' , $request->input('nombre'))->first();
        if ($catalogoBonificacion) {
            if ($catalogoBonificacion->trashed()) {
                return response()->json([
                    'message' => 'La bonificación ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                    'restore' => true,
                    'bonificacion_id' => $catalogoBonificacion->id
                ], 200);
            }
            return response()->json([
                'message' => 'La bonificación ya existe',
                'restore' => false
            ], 200);
            
        }

        //Si no existe la bonificación, la crea

        if (!$catalogoBonificacion) {
            $bonificacion = CatalogoBonificacion::create($data);
            return response(new CatalogoBonificacionResource($bonificacion), 201);
        }

        //$data = $request->validated();

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $catalogoBonificacion = CatalogoBonificacion::findOrFail($id);
            return response(new CatalogoBonificacionResource($catalogoBonificacion), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la bonificación'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCatalogoBonificacionRequest $request, CatalogoBonificacion $catalogoBonificacion)
    {     
            $data = $request->validated();
            $catalogoBonificacion = CatalogoBonificacion::find($request["id"]);
            //Condicion para buscar si existe la bonificación que se busco
            if ($catalogoBonificacion) {
                return response()->json([
                    //Si se encuentra registrado, regresa un mensaje de true, junto con el id de la bonificacion modificada
                    'message' => 'Se encuentra registrado',
                    'restore' => true,
                    'bonificacion_id' => $catalogoBonificacion->id,
                    $catalogoBonificacion->update($data),
                    $catalogoBonificacion->save(),
                ], 200);
                //$catalogoBonificacion->update($data);
            }
            //En dado caso que la bonificacion este borrada logicamente, arroja un error 500 (no existe)
            elseif(!$catalogoBonificacion){
                return response()->json([
                    'message' => 'Ocurrio un error'
                ] , 500);
            }
           
            return new CatalogoBonificacionResource($catalogoBonificacion);
   
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatalogoBonificacion $catalogoBonificacion, Request $request)
    {
        try {
        $catalogoBonificacion = CatalogoBonificacion::findOrFail($request->id);
        $catalogoBonificacion->delete();
        return response()->json(['message', 'Eliminado correctamente', 200]);

        } catch (\Exception $e) {
            return response()->json(['message', 'Algo falló', 500]);
        }
        /*
       $bonificacion = CatalogoBonificacion::find($request["id"]);
       $bonificacion->delete(); */
    }

    public function restaurarDato (CatalogoBonificacion $catalogoBonificacion, Request $request)
    {
        $catalogoBonificacion = CatalogoBonificacion::withTrashed()->findOrFail($request->id);
        //Condicion para verificar si el registro esta eliminado
        if ($catalogoBonificacion->trashed()) {
            //Restaura el registro
            $catalogoBonificacion->restore();
            return response()->json(['message' => 'La bonificación ha sido restaurada' , 200]);
        }
    }
}
