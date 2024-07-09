<?php

namespace App\Http\Controllers\Api;

use App\Models\DescuentoCatalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DescuentoCatalogoResource;
use App\Http\Requests\StoreDescuentoCatalogoRequest;
use App\Http\Requests\UpdateDescuentoCatalogoRequest;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DescuentoCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(DescuentoCatalogoResource::collection(
                DescuentoCatalogo::all()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los descuentos'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDescuentoCatalogoRequest $request)
    {
        /*try{
            $data = $request->validated();
            $descuento = DescuentoCatalogo::create($data);
            return response(new DescuentoCatalogoResource($descuento), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el descuento'
            ], 500);
        }*/
        //Se valida el store
        $data = $request->validated();
        //Busca por nombre los eliminados
        $descuento = DescuentoCatalogo::withTrashed()->where('nombre' , $request->input('nombre'))->first();
        if ($descuento) {
            if ($descuento->trashed()) {
                return response()->json([
                    'message' => 'El descuento ya existe pero ha sido eliminada, ¿Desea restaurarla?',
                    'restore' => true,
                    'descuento_id' => $descuento->id
                ], 200);
            }
            return response()->json([
                'message' => 'El descuento ya existe',
                'restore' => false
            ], 200);
        }
        //Si no existe el descuento, lo crea
        if (!$descuento) {
            $descuento = DescuentoCatalogo::create($data);
            return response(new DescuentoCatalogo($descuento), 201);
        }
        //$data = $request->validated();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $descuento = DescuentoCatalogo::findOrFail($id);
            return response(new DescuentoCatalogoResource($descuento), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el descuento'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDescuentoCatalogoRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $descuento = DescuentoCatalogo::findOrFail($id);
            $descuento->update($data);
            $descuento->save();
            return response(new DescuentoCatalogoResource($descuento), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el descuento'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $descuento = DescuentoCatalogo::findOrFail($id);
            $descuento->delete();
            return response("Descuento eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el descuento'
            ], 500);
        }
    }

    public function restaurarDato (DescuentoCatalogo $catalogoDescuento, Request $request)
    {
        $catalogoDescuento = DescuentoCatalogo::withTrashed()->findOrFail($request->id);
        //Condicion para verificar si el registro esta eliminado
        if ($catalogoDescuento->trashed()) {
            //Restaura el registro
            $catalogoDescuento->restore();
            return response()->json(['message' => 'El descuento ha sido restaurado' , 200]);
        }
    }
}