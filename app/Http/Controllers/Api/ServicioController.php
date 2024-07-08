<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Servicio;
use App\Http\Requests\StoreServicioRequest;
use App\Http\Requests\UpdateServicioRequest;
use App\Http\Resources\ServicioResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ServicioController extends Controller
{
     /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(ServicioResource::collection(
                Servicio::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los servicios'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServicioRequest $request)
    {
        try{
            $data = $request->validated();
            $servicio = Servicio::create($data);
            return response(new ServicioResource($servicio), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el servicio'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            return response(new ServicioResource($servicio), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el servicio'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateServicioRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $servicio = Servicio::findOrFail($id);
            $servicio->update($data);
            $servicio->save();
            return response(new ServicioResource($servicio), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el servicio'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $servicio = Servicio::findOrFail($id);
            $servicio->delete();
            return response("Servicio eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el servicio'
            ], 500);
        }
    }
}
