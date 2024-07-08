<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TipoToma;
use App\Http\Requests\StoreTipoTomaRequest;
use App\Http\Requests\UpdateTipoTomaRequest;
use App\Http\Resources\TipoTomaResource;
use Exception;
use Illuminate\Http\Request;

class Tipo_tomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return TipoTomaResource::collection(
                TipoToma::all()
            );
        }
        catch(Exception $ex){
            return response()->json(['message' => 'No se encontro el tipo de toma'], 200);
        }
        
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
    public function show(string $tipoToma)
    {
        try{
            $data = TipoToma::whereRaw("nombre LIKE ?", ['%'.$tipoToma.'%'])->get();
            return TipoTomaResource::collection(
                $data
            );
        }
        catch(Exception $Ex){
            return response()->json(['message' => 'No se encontro el tipo de toma'], 200);

        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTipoTomaRequest $request, TipoToma $tipoToma)
    {
        try{
            $data=$request->validated();
            $usuario=TipoToma::findorFail($request['id']);
            $usuario->update($data);
            $usuario->save();
            return new TipoTomaResource($usuario);
        }
        catch(Exception $ex){
            throw new Exception($ex);
        }
       
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
    public function restaurarDato(TipoToma $TipoToma, Request $request)
    {

        $TipoToma = TipoToma::withTrashed()->findOrFail($request->id);

           // Verifica si el registro está eliminado
        if ($TipoToma->trashed()) {
            // Restaura el registro
            $TipoToma->restore();
            return response()->json(['message' => 'El tipo de toma ha sido restaurado.'], 200);
        }

    }
}
