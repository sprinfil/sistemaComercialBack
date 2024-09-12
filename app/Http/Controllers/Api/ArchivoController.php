<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArchivoRequest;
use App\Http\Resources\ArchivoResource;
use App\Models\Archivo;
use App\Services\ArchivoService;
use Exception;

class ArchivoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // public function index()
    // {
    //     try{
    //         return response(AbonoResource::collection(
    //             Abono::all()
    //         ),200);
    //     } catch(Exception $e) {
    //         return response()->json([
    //             'error' => 'No fue posible consultar el abono'
    //         ], 500);
    //     }
    // }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreArchivoRequest $request)
    {
        try {
            return response()->json(new ArchivoResource((new ArchivoService())->subir($request)), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el archivo' . $e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    // public function show($id)
    // {
    //     try {
    //         $abono = Abono::findOrFail($id);
    //         return response(new AbonoResource($abono), 200);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'error' => 'No se pudo encontrar el abono'
    //         ], 500);
    //     }
    // }

    /**
     * Update the specified resource in storage.
     */
    // public function update(UpdateAbonoRequest $request, $id)
    // {
    //     try {
    //         $data = $request->validated();
    //         $abono = Abono::findOrFail($id);
    //         $abono->update($data);
    //         $abono->save();
    //         return response(new Abono($abono), 200);
    //     } catch (Exception $e) {
    //         return response()->json([
    //             'error' => 'No se pudo editar el cargo'
    //         ], 500);
    //     }
    // }

    /**
     * Remove the specified resource from storage.
     */
    // public function destroy($id)
    // {
    //     try {
    //         $abono = Abono::findOrFail($id);
    //         $abono->delete();
    //         return response("Abono eliminado con exito",200);
    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'error' => 'No se pudo borrar el abono'
    //         ], 500);
    //     }
    // }
}
