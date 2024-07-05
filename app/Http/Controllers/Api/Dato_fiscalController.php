<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Dato_fiscal;
use App\Http\Requests\StoreDato_fiscalRequest;
use App\Http\Requests\UpdateDato_fiscalRequest;
use App\Http\Resources\Dato_fiscalResource;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class Dato_fiscalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Dato_fiscalResource::collection(
            Dato_fiscal::all()
        );
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDato_fiscalRequest $request)
    {
        $data = $request->validated();
        $dato_fiscal = Dato_fiscal::create($data);
        return response(new Dato_fiscalResource($dato_fiscal), 201);
        //
    }

    /**
     * Display the specified resource.
     */
    //show(Dato_fiscal $dato_fiscal)
    public function show(Dato_fiscal $dato_fiscal, Request $request)
    {
        //Falta consulta especifica 
        try {
            $dato_fiscal = Dato_fiscal::findOrFail($request["id"]);
            return response(new Dato_fiscalResource($dato_fiscal), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el Registro fiscal'
            ], 500);
            //
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDato_fiscalRequest $request, Dato_fiscal $dato_fiscal)
    {
        $data = $request->validated();
        $dato_fiscal = Dato_fiscal::find($request["id"]);
        $dato_fiscal->update($data);
        $dato_fiscal->save();
        return new Dato_fiscalResource($dato_fiscal);
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dato_fiscal $dato_fiscal, Request $request)
    {
        $dato_fiscal = Dato_fiscal::find($request["id"]);
        $dato_fiscal->delete();
        //
    }
}
