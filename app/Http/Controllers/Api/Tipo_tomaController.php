<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoToma;
use App\Http\Requests\StoreTipoTomaRequest;
use App\Http\Requests\UpdateTipoTomaRequest;
use App\Http\Resources\TipoTomaResource;
use Illuminate\Http\Request;

class Tipo_tomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return TipoTomaResource::collection(
            TipoToma::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTipoTomaRequest $request)
    {
        $data=$request->validated();
        $Tipotoma=TipoToma::create($data);
        return response(new TipoTomaResource($Tipotoma),201);
    }

    /**
     * Display the specified resource.
     */
    public function show(TipoToma $tipoToma)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoTomaRequest $request, TipoToma $tipoToma)
    {
        $data=$request->validated();
        $usuario=TipoToma::find($request['id']);
        $usuario->update($data);
        $usuario->save();
        return new TipoTomaResource($usuario);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TipoToma $tipoToma,Request $request)
    {
        try
        {
            $tipoToma = TipoToma::findOrFail($request["id"]);
            $tipoToma->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'Algo fallo'], 500);
        }
    }
}
