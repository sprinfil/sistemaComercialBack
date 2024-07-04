<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ConstanciaCatalogo;
use App\Http\Controllers\Controller;
use App\Models\GiroComercialCatalogo;
use App\Http\Resources\GiroComercialCatalogoResource;
use App\Http\Requests\StoreGiroComercialCatalogoRequest;
use App\Http\Requests\UpdateGiroComercialCatalogoRequest;

class GiroComercialCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return GiroComercialCatalogoResource::collection(
            GiroComercialCatalogo::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreGiroComercialCatalogoRequest $request)
    {
        $data = $request->validated();
        $girocomercial = GiroComercialCatalogo::create($data);
        return response(new GiroComercialCatalogoResource($girocomercial), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(GiroComercialCatalogo $giroComercialCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateGiroComercialCatalogoRequest $request, GiroComercialCatalogo $giroComercialCatalogo)
    {
        $data = $request->validated();
        $girocomercial = GiroComercialCatalogo::find($request["id"]);
        $girocomercial->update($data);
        $girocomercial->save();
        return new GiroComercialCatalogoResource($girocomercial);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(GiroComercialCatalogo $giroComercialCatalogo, Request $request)
    {
        $girocomercial = GiroComercialCatalogo::find($request["id"]);
        $girocomercial->delete();
    }
}
