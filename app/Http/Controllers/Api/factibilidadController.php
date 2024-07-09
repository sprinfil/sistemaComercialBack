<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\factibilidad;
use App\Http\Requests\StorefactibilidadRequest;
use App\Http\Requests\UpdatefactibilidadRequest;
use App\Http\Resources\factibilidadResource;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class factibilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(factibilidadResource::collection(
                factibilidad::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar una factibilidad'.$e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(factibilidad $factibilidad , StorefactibilidadRequest $request)
    {
        try{
            $data = $request->validated();
            $factibilidad = factibilidad::create($data);
                return response(new factibilidadResource($factibilidad), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la factibilidad'.$e
            ], 500);
        }
        /*
        $factibilidadAux = Factibilidad::join('contratos' , 'factibilidad.contratos_id' ,
         'contratos.factibilidad_id')->get(); aux */

        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $factibilidad = factibilidad::findOrFail($id);
            return response(new factibilidadResource($factibilidad), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la factibilidad'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatefactibilidadRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $factibilidad = factibilidad::findOrFail($id);
            $factibilidad->update($data);
            $factibilidad->save();
            return response(new factibilidadResource($factibilidad), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la factibilidad'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(factibilidad $factibilidad, $id)
    {
        try {
            $factibilidad = factibilidad::findOrFail($id);
            $factibilidad->delete();
            return response("Factibilidad eliminada",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar la factibilidad'
            ], 500);
        }
    }
    
    public function restaurar (factibilidad $factibilidad, Request $request)
    {
        try {
            $factibilidad = factibilidad::withTrashed()->findOrFail($request->id);
            //Condicion para verificar si el registro esta eliminado
            if ($factibilidad->trashed()) {
                //Restaura el registro
                $factibilidad->restore();
                return response()->json(['message' => 'La factibilidad ha sido restaurada con exito' , 200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Hubo un error al restaurar la factibilidad'
            ]);
        }

    }

}
