<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ConstanciaCatalogo;
use App\Http\Controllers\Controller;
use App\Models\GiroComercialCatalogo;
use App\Http\Resources\GiroComercialCatalogoResource;
use App\Http\Requests\StoreGiroComercialCatalogoRequest;
use App\Http\Requests\UpdateGiroComercialCatalogoRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class GiroComercialCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response(GiroComercialCatalogoResource::collection(
            GiroComercialCatalogo::all()
        ),200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGiroComercialCatalogoRequest $request)
    {
        try{
            $data = $request->validated();
            $girocomercial = GiroComercialCatalogo::create($data);
            return response(new GiroComercialCatalogoResource($girocomercial), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el giro comercial'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $girocomercial = GiroComercialCatalogo::findOrFail($id);
            return response(new GiroComercialCatalogoResource($girocomercial), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el giro comercial'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGiroComercialCatalogoRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $girocomercial = GiroComercialCatalogo::findOrFail($id);
            $girocomercial->update($data);
            $girocomercial->save();
            return response(new GiroComercialCatalogoResource($girocomercial), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el giro comercial'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $girocomercial = GiroComercialCatalogo::findOrFail($id);
            $girocomercial->delete();
            return response("Giro comercial eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el giro comercial'
            ], 500);
        }
    }
}
