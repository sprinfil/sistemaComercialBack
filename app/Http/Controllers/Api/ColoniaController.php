<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Colonia;
use App\Http\Requests\StoreColoniaRequest;
use App\Http\Requests\UpdateColoniaRequest;
use App\Http\Resources\ColoniaResource;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ColoniaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(ColoniaResource::collection(
                Colonia::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar la colonia'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreColoniaRequest $request)
    {
        try{
            $data = $request->validated();
            //valida trash
            $colonia = Colonia::withTrashed()->where('nombre', $request->input('nombre'))->first();

            //validacion en caso de que la colonia ya este registrado en le base de datos
            if ($colonia) {
                if ($colonia->trashed()) {
                    return response()->json([
                        'message' => 'La colonia ya existe pero ha sido eliminado. ¿Desea restaurarla?',
                        'restore' => true,
                        'colonia_id' => $colonia->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'La colonia ya existe.',
                    'restore' => false
                ], 200);
            } else {
                // si el concepto no existe, lo crea
                $colonia = Colonia::create($data);
                return response(new ColoniaResource($colonia), 201);
            }  
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la colonia'
            ], 500);
        }
    }

    public function restaurarDato(Colonia $colonia, Request $request)
    {
        $colonia = Colonia::withTrashed()->findOrFail($request->id);
        //verifica si el registro está eliminado
        if ($colonia->trashed()) {
            //restaura el registro
            $colonia->restore();
            return response()->json(['message' => 'El colonia ha sido restaurado'], 200);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $colonia = Colonia::findOrFail($id);
            return response(new ColoniaResource($colonia), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la colonia'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateColoniaRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $colonia = Colonia::findOrFail($id);
            $colonia->update($data);
            $colonia->save();
            return response(new ColoniaResource($colonia), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la colonia'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $colonia = Colonia::findOrFail($id);
            $colonia->delete();
            return response("Colonia eliminada con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar la colonia'
            ], 500);
        }
    }
}
