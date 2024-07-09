<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cargo;
use App\Http\Requests\StoreCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use App\Http\Resources\CargoResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CargoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(CargoResource::collection(
                Cargo::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar el cargo'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCargoRequest $request)
    {
        try{
            $data = $request->validated();
            $cargo = Cargo::create($data);
            return response(new CargoResource($cargo), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el cargo'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $cargo = Cargo::findOrFail($id);
            return response(new CargoResource($cargo), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el cargo'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCargoRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $cargo = Cargo::findOrFail($id);
            $cargo->update($data);
            $cargo->save();
            return response(new CargoResource($cargo), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el cargo'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $cargo = Cargo::findOrFail($id);
            $cargo->delete();
            return response("Cargo eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el cargo'
            ], 500);
        }
    }
}
