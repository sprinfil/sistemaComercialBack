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
        /*
        $data = $request->validated();
        $ajuste = AjusteCatalogo::create($data);
        return response(new AjusteCatalogoResource($ajuste), 201);
        */

        //Se valida el store
        $data = $request->validated();
        //Busca por nombre los eliminados
        $ajuste = AjusteCatalogo::withTrashed()->where('nombre' , $request->input('nombre'))->first();
        if ($ajuste) {
            if ($ajuste->trashed()) {
                return response()->json([
                    'message' => 'El ajuste ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                    'restore' => true,
                    'ajuste_id' => $ajuste->id
                ], 200);
            }
            return response()->json([
                'message' => 'El ajuste ya existe',
                'restore' => false
            ], 200);
        }
        //Si no existe el ajuste, la crea
        if (!$ajuste) {
            $ajuste = AjusteCatalogo::create($data);
            return response($ajuste, 201);
        }
        //$data = $request->validated();
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

    public function restaurarDato (AjusteCatalogo $convenioCatalogo, Request $request)
    {
        $convenioCatalogo = AjusteCatalogo::withTrashed()->findOrFail($request->id);
        //Condicion para verificar si el registro esta eliminado
        if ($convenioCatalogo->trashed()) {
            //Restaura el registro
            $convenioCatalogo->restore();
            return response()->json(['message' => 'El ajuste ha sido restaurado' , 200]);
        }
    }
}
