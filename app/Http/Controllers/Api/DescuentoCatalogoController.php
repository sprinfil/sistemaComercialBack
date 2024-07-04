<?php

namespace App\Http\Controllers\Api;

use App\Models\DescuentoCatalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DescuentoCatalogoResource;
use App\Http\Requests\StoreDescuentoCatalogoRequest;
use App\Http\Requests\UpdateDescuentoCatalogoRequest;

class DescuentoCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return DescuentoCatalogoResource::collection(
            DescuentoCatalogo::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDescuentoCatalogoRequest $request)
    {
        $data = $request->validated();
        $descuentoCatalogo = DescuentoCatalogo::create($data);
        return response(new DescuentoCatalogoResource($descuentoCatalogo), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(DescuentoCatalogo $descuentoCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDescuentoCatalogoRequest $request, DescuentoCatalogo $descuentoCatalogo)
    {
        $data = $request->validated();
        $descuentoCatalogo = DescuentoCatalogo::find($request["id"]);
        $descuentoCatalogo->update($data);
        $descuentoCatalogo->save();
        return new DescuentoCatalogoResource($descuentoCatalogo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DescuentoCatalogo $descuentoCatalogo, Request $request)
    {
        $descuentoCatalogo = DescuentoCatalogo::find($request["id"]);
        $descuentoCatalogo->delete();
        return response("",201);
    }
}
