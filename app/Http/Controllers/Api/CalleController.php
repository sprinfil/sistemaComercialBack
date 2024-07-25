<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calle;
use App\Http\Requests\StoreCalleRequest;
use App\Http\Requests\UpdateCalleRequest;
use App\Http\Resources\CalleResource;
use App\Models\Colonia;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CalleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(CalleResource::collection(
                Calle::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar la calle'
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function getCallesPorColonia($id)
    {
        try{
            return response(CalleResource::collection(
                Colonia::find($id)->calles
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar la calle'.$e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCalleRequest $request)
    {
        try{
            $data = $request->validated();
            //valida trash
            $calle = Calle::withTrashed()->where('nombre', $request->input('nombre'))->first();

            //validacion en caso de que la calle ya este registrado en le base de datos
            if ($calle) {
                if ($calle->trashed()) {
                    return response()->json([
                        'message' => 'La colonia ya existe pero ha sido eliminada. ¿Desea restaurarla?',
                        'restore' => true,
                        'calle_id' => $calle->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'La calle ya existe.',
                    'restore' => false
                ], 200);
            } else {
                // si la calle no existe, la crea
                $calle = Calle::create($data);
                return response(new CalleResource($calle), 201);
            }
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la calle'
            ], 500);
        }
    }

    public function restaurarDato(Calle $calle, Request $request)
    {
        $calle = Calle::withTrashed()->findOrFail($request->id);
        //verifica si el registro está eliminado
        if ($calle->trashed()) {
            //restaura el registro
            $calle->restore();
            return response()->json(['message' => 'La calle ha sido restaurado'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $calle = Calle::findOrFail($id);
            return response(new CalleResource($calle), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la calle'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCalleRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $calle = Calle::findOrFail($id);
            $calle->update($data);
            $calle->save();
            return response(new CalleResource($calle), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la calle'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $calle = Calle::findOrFail($id);
            $calle->delete();
            return response("Calle eliminada con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar la calle'
            ], 500);
        }
    }
}
