<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CatalogoBonificacion;
use App\Http\Requests\StoreCatalogoBonificacionRequest;
use App\Http\Requests\UpdateCatalogoBonificacionRequest;
use App\Http\Resources\CatalogoBonificacionResource;
use Illuminate\Http\Request;

class CatalogoBonificacionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       return CatalogoBonificacionResource::collection(
        CatalogoBonificacion::all()
       );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCatalogoBonificacionRequest $request)
    {
        $data = $request->validated();
        $bonificacion = CatalogoBonificacion::create($data);
        return response(new CatalogoBonificacionResource($bonificacion), 201);

    }

    /**
     * Display the specified resource.
     */
    public function show(CatalogoBonificacion $catalogoBonificacion)
    {
      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCatalogoBonificacionRequest $request, CatalogoBonificacion $catalogoBonificacion)
    {
        $data = $request->validated();
        $bonificacion = CatalogoBonificacion::find($request["id"]);
        $bonificacion->update($data);
        $bonificacion->save();
        return new CatalogoBonificacionResource($bonificacion);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CatalogoBonificacion $catalogoBonificacion, Request $request)
    {
       $bonificacion = CatalogoBonificacion::find($request["id"]);
       $bonificacion->delete();
    }
}
