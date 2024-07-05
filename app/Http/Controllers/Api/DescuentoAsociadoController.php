<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DescuentoAsociado;
use App\Http\Requests\StoreDescuentoAsociadoRequest;
use App\Http\Requests\UpdateDescuentoAsociadoRequest;
use App\Http\Resources\DescuentoAsociadoResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DescuentoAsociadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(DescuentoAsociadoResource::collection(
                DescuentoAsociado::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los descuentos'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDescuentoAsociadoRequest $request)
    {
        try{
            $data = $request->validated();
            $descuento = DescuentoAsociado::create($data);
            return response(new DescuentoAsociadoResource($descuento), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el descuento'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $descuento = DescuentoAsociado::findOrFail($id);
            return response(new DescuentoAsociadoResource($descuento), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el descuento'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDescuentoAsociadoRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $descuento = DescuentoAsociado::findOrFail($id);
            $descuento->update($data);
            $descuento->save();
            return response(new DescuentoAsociadoResource($descuento), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el descuento'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $descuento = DescuentoAsociado::findOrFail($id);
            $descuento->delete();
            return response("Descuento eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el descuento'
            ], 500);
        }
    }
}
