<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\cargoDirecto;
use App\Http\Requests\StorecargoDirectoRequest;
use App\Http\Requests\UpdatecargoDirectoRequest;
use App\Http\Resources\cargoDirectoResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class cargoDirectoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(cargoDirectoResource::collection(     
                cargoDirecto::join('cargos' 
                , 'cargo_directo.id_cargo'
                , '=' ,
                'cargos.id')
                ->select('cargo_directo.id' , 'cargo_directo.id_cargo' , 'cargos.id_origen',
                'cargos.modelo_origen' , 'cargos.id_dueño' , 'cargos.modelo_dueño',
                'cargos.monto', 'cargos.estado' , 'cargos.fecha_cargo' ,
                'cargos.fecha_liquidacion')->get()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar el cargo directo' .$e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecargoDirectoRequest $request)
    {
        try{
            $data = $request->validated();
            $cargoDirecto = cargoDirecto::create($data);
            return response(new cargoDirectoResource($cargoDirecto), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el cargo directo'.$e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $cargoDirecto = cargoDirecto::findOrFail($id);
            return response(new cargoDirectoResource($cargoDirecto), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el cargo directo'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecargoDirectoRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $cargoDirecto = cargoDirecto::findOrFail($id);
            $cargoDirecto->update($data);
            $cargoDirecto->save();
            //return json_encode($cargoDirecto);
            return response(new cargoDirectoResource($cargoDirecto), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el cargo directo'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $cargoDirecto = cargoDirecto::findOrFail($id);
            $cargoDirecto->delete();
            return response("Cargo eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el cargo directo'
            ], 500);
        }
    }
}
