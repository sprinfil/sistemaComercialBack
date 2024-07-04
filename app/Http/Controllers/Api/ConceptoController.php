<?php

namespace App\Http\Controllers\Api;

use App\Models\ConceptoCatalogo;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConceptoResource;
use App\Http\Requests\StoreConceptoCatalogoRequest;
use App\Http\Requests\UpdateConceptoCatalogoRequest;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ConceptoResource::collection(
            ConceptoCatalogo::orderby("id", "desc")->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConceptoCatalogoRequest $request)
    {
        $data = $request->validated();
        $concepto = ConceptoCatalogo::create($data);
        return response(new ConceptoResource($concepto), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ConceptoCatalogo $conceptoCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConceptoCatalogoRequest $request, ConceptoCatalogo $conceptoCatalogo)
    {
        $data = $request->validated();
        $conceptoCatalogo = ConceptoCatalogo::find($request["id"]);
        $conceptoCatalogo->update($data);
        $conceptoCatalogo->save();
        return new ConceptoResource($conceptoCatalogo);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConceptoCatalogo $conceptoCatalogo,Request $request)
    {
        try
        {
            $conceptoCatalogo = ConceptoCatalogo::findOrFail($request->id);
            $conceptoCatalogo->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'Algo fallo'], 500);
        }
    }
}
