<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Punto;
use App\Http\Requests\StorePuntoRequest;
use App\Http\Requests\UpdatePuntoRequest;
use App\Http\Resources\PuntoResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PuntoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePuntoRequest $request)
    {
        // pendiente de permisos
        try {
            //code...
            $data = $request->validated();
            if ($data) {
                $punto = Punto::create($data);
                return new PuntoResource($punto);
            }else{
                return response()->json([
                    'error' => 'Ocurrio un problema al registrar el punto'
                ], 500);
            }
           
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Ocurrio un problema al registrar el punto'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
         // pendiente permiso
         try {

            $punto = Punto::findOrFail($id);
            if ($punto) {
                return response(new PuntoResource($punto), 200);
            }else{
                return response()->json([
                    'error' => 'No se encontro el punto'
            ], 500);
            }
            

        } catch (ModelNotFoundException $e) {
            return response()->json([
                    'error' => 'No se pudo encontrar el punto'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePuntoRequest $request, string $id)
    {
        //pendiente de permisos
        try {
            $data = $request->validated();
            $punto = Punto::findOrFail($id);
            $punto->update($data);
            $punto->save();
            return response(new PuntoResource($punto), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al editar el punto'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Punto $punto)
    {
        //
    }
}
