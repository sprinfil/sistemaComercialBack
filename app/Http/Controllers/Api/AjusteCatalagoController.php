<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AjusteCatalogo;
use App\Http\Requests\StoreAjusteCatalogoRequest;
use App\Http\Requests\UpdateAjusteCatalogoRequest;
use App\Http\Resources\AjusteCatalogoResource;
use Illuminate\Http\Request;

class AjusteCatalagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return AjusteCatalogoResource::collection(
            AjusteCatalogo::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAjusteCatalogoRequest $request)
    {
        $data = $request->validated();
        $ajuste = AjusteCatalogo::create($data);
        return response(new AjusteCatalogoResource($ajuste), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(AjusteCatalogo $ajusteCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAjusteCatalogoRequest $request, AjusteCatalogo $ajusteCatalogo)
    {
        $data = $request->validated();
        $ajuste = AjusteCatalogo::find($request["id"]);
        $ajuste->update($data);
        $ajuste->save();
        return new AjusteCatalogoResource($ajuste);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AjusteCatalogo $ajusteCatalogo, Request $request)
    {
        $ajuste = AjusteCatalogo::find($request["id"]);
        $ajuste->delete();
    }
}
