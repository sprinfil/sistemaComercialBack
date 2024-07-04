<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConstanciaCatalogo;
use App\Http\Requests\StoreCosntanciaCatalogoRequest;
use App\Http\Requests\UpdateCosntanciaCatalogoRequest;
use App\Http\Resources\ConstanciaCatalogoResource;

class ConstanciaCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ConstanciaCatalogoResource::collection(
            ConstanciaCatalogo::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCosntanciaCatalogoRequest $request)
    {
        $data = $request->validated();
        $cosntancia = ConstanciaCatalogo::create($data);
        return response(new ConstanciaCatalogoResource($cosntancia), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ConstanciaCatalogo $cosntanciaCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCosntanciaCatalogoRequest $request, ConstanciaCatalogo $cosntanciaCatalogo)
    {
        $data = $request->validated();
        $constancia = ConstanciaCatalogo::find($request["id"]);
        $constancia->update($data);
        $constancia->save();
        return new ConstanciaCatalogoResource($constancia);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConstanciaCatalogo $cosntanciaCatalogo, Request $request)
    {
        $constancia = ConstanciaCatalogo::find($request["id"]);
        $constancia->delete();
    }
}
