<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DatosDomiciliacion;
use App\Http\Requests\StoreDatosDomiciliacionRequest;
use App\Http\Requests\UpdateDatosDomiciliacionRequest;
use App\Http\Resources\DatosDomiciliacionResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DatosDomiciliacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(DatosDomiciliacionResource::collection(
                DatosDomiciliacion::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los datos de domiciliacion'.$e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDatosDomiciliacionRequest $request)
    {
        try{
            $data = $request->validated();
            $datos = DatosDomiciliacion::create($data);
            return response(new DatosDomiciliacionResource($datos), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar los datos de domiciliacion'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $datos = DatosDomiciliacion::findOrFail($id);
            return response(new DatosDomiciliacionResource($datos), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar los datos de domiciliacion'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDatosDomiciliacionRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $datos = DatosDomiciliacion::findOrFail($id);
            $datos->update($data);
            $datos->save();
            return response(new DatosDomiciliacionResource($datos), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar los datos de domiciliacion'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $datos = DatosDomiciliacion::findOrFail($id);
            $datos->delete();
            return response("Los datos de domiciliacion se han eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar los datos de domiciliacion'
            ], 500);
        }
    }
}
