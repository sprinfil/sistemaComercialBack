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
        $this->authorize('viewAny', ConstanciaCatalogo::class);
        return ConstanciaCatalogoResource::collection(
            ConstanciaCatalogo::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCosntanciaCatalogoRequest $request)
    {
        $this->authorize('create', ConstanciaCatalogo::class);
        /*
        $data = $request->validated();
        $cosntancia = ConstanciaCatalogo::create($data);
        return response(new ConstanciaCatalogoResource($cosntancia), 201);
        */
        $data = $request->validated();
        //Busca por nombre los eliminados
        $constancia = ConstanciaCatalogo::withTrashed()->where('nombre' , $request->input('nombre'))->first();
        if ($constancia) {
            if ($constancia->trashed()) {
                return response()->json([
                    'message' => 'La constancia ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                    'restore' => true,
                    'constancia_id' => $constancia->id
                ], 200);
            }
            return response()->json([
                'message' => 'La constancia ya existe',
                'restore' => false
            ], 200);
        }
        //Si no existe la constancia, la crea
        if (!$constancia) {
            $constancia = ConstanciaCatalogo::create($data);
            return response($constancia, 201);
        }
        //$data = $request->validated();
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
        $this->authorize('update', ConstanciaCatalogo::class);
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
        $this->authorize('delete', ConstanciaCatalogo::class);
        $constancia = ConstanciaCatalogo::find($request["id"]);
        $constancia->delete();
    }

    public function restaurarDato (ConstanciaCatalogo $constanciaCatalogo, Request $request)
    {
        $constanciaCatalogo = ConstanciaCatalogo::withTrashed()->findOrFail($request->id);
        //Condicion para verificar si el registro esta eliminado
        if ($constanciaCatalogo->trashed()) {
            //Restaura el registro
            $constanciaCatalogo->restore();
            return response()->json(['message' => 'La constancia ha sido restaurado' , 200]);
        }
    }
}
