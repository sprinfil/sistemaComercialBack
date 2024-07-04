<?php

namespace App\Http\Controllers\Api;

use App\Models\DescuentoCatalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DescuentoCatalogoResource;
use App\Http\Requests\StoreDescuentoCatalogoRequest;
use App\Http\Requests\UpdateDescuentoCatalogoRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DescuentoCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(DescuentoCatalogoResource::collection(
                DescuentoCatalogo::all()
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
    public function store(StoreDescuentoCatalogoRequest $request)
    {
        try{
            $data = $request->validated();
            $descuento = DescuentoCatalogo::create($data);
            return response(new DescuentoCatalogoResource($descuento), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el descuento'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $descuento = DescuentoCatalogo::findOrFail($id);
            return response(new DescuentoCatalogoResource($descuento), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el descuento'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDescuentoCatalogoRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $descuento = DescuentoCatalogo::findOrFail($id);
            $descuento->update($data);
            $descuento->save();
            return response(new DescuentoCatalogoResource($descuento), 200);
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
            $descuento = DescuentoCatalogo::findOrFail($id);
            $descuento->delete();
            return response("Descuento eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el descuento'
            ], 500);
        }
    }
}