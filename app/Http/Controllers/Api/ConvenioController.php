<?php

namespace App\Http\Controllers\Api;

use App\Models\ConvenioCatalogo;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConvenioResource;
use App\Http\Requests\StoreConvenioCatalogoRequest;
use App\Http\Requests\UpdateConvenioCatalogoRequest;
use Illuminate\Http\Request;

class ConvenioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ConvenioResource::collection(
            ConvenioCatalogo::orderby("id", "desc")->get()
        );

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConvenioCatalogoRequest $request)
    {
       /*$data = $request->validated();
       $convenio = ConvenioCatalogo::create($data);
       return response(new ConvenioResource($convenio), 201);*/
       //Se valida el store
       $data = $request->validated();
       //Busca por nombre los eliminados
       $convenio = ConvenioCatalogo::withTrashed()->where('nombre' , $request->input('nombre'))->first();
       if ($convenio) {
           if ($convenio->trashed()) {
               return response()->json([
                   'message' => 'El convenio ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                   'restore' => true,
                   'convenio_id' => $convenio->id
               ], 200);
           }
           return response()->json([
               'message' => 'El convenio ya existe',
               'restore' => false
           ], 200);
       }
       //Si no existe el convenio, lo crea
       if (!$convenio) {
           $convenio = ConvenioCatalogo::create($data);
           return response($convenio, 201);
       }
       //$data = $request->validated();
    }

    /**
     * Display the specified resource.
     */
    public function show(ConvenioCatalogo $convenioCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConvenioCatalogoRequest $request, ConvenioCatalogo $convenioCatalogo)
    {
        $data = $request->validated();
        $convenioCatalogo = ConvenioCatalogo::find($request["id"]);
        $convenioCatalogo->update($data);
        $convenioCatalogo->save();
        return new ConvenioResource($convenioCatalogo);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConvenioCatalogo $convenioCatalogo, Request $request)
    {
        try
        {

            $convenioCatalogo = ConvenioCatalogo::findOrFail($request->id);
            $convenioCatalogo->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'Algo fallo'], 500);
        }

    }

    public function restaurarDato (ConvenioCatalogo $convenioCatalogo, Request $request)
    {
        $convenioCatalogo = ConvenioCatalogo::withTrashed()->findOrFail($request->id);
        //Condicion para verificar si el registro esta eliminado
        if ($convenioCatalogo->trashed()) {
            //Restaura el registro
            $convenioCatalogo->restore();
            return response()->json(['message' => 'El convenio ha sido restaurado' , 200]);
        }
    }
}
