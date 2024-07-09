<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Medidor;
use App\Http\Requests\StoreMedidorRequest;
use App\Http\Requests\UpdateMedidorRequest;
use App\Http\Resources\MedidorResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MedidorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(MedidorResource::collection(
                Medidor::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los medidiores'.$e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreMedidorRequest $request)
    {
        try{
            $data = $request->validated();
            $medidor = Medidor::create($data);
            return response(new MedidorResource($medidor), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el medidor'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $medidor = Medidor::findOrFail($id);
            return response(new MedidorResource($medidor), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el medidor'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateMedidorRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $medidor = Medidor::findOrFail($id);
            $medidor->update($data);
            $medidor->save();
            return response(new MedidorResource($medidor), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el medidor'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $medidor = Medidor::findOrFail($id);
            $medidor->delete();
            return response("Medidor eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el medidor'
            ], 500);
        }
    }
}
