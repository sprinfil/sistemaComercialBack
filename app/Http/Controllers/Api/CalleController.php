<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Calle;
use App\Http\Requests\StoreCalleRequest;
use App\Http\Requests\UpdateCalleRequest;
use App\Http\Resources\CalleResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
     * Store a newly created resource in storage.
     */
    public function store(StoreCalleRequest $request)
    {
        try{
            $data = $request->validated();
            $calle = Calle::create($data);
            return response(new CalleResource($calle), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la calle'
            ], 500);
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
