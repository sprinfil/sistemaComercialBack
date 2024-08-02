<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CorreccionSolicitud;
use App\Http\Requests\StoreCorreccionSolicitudRequest;
use App\Http\Requests\UpdateCorreccionSolicitudRequest;
use App\Http\Resources\CorreccionSolicitudResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CorreccionSolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return CorreccionSolicitudResource::collection(
            correccionSolicitud::all()
        );
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecorreccionSolicitudRequest $request)
    {
        try{
            $data = $request->validated();
            $correccionSolicitud = correccionSolicitud::create($data);
            return response(new CorreccionSolicitudResource($correccionSolicitud), 201);
            
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la solicitud de correccion'
            ], 500);
        }
         
    }

    /**
     * Display the specified resource.
     */
    public function show(CorreccionSolicitud $correccionSolicitud, Request $request)
    {
        //Falta consulta especifica 
        try {
            $correccionSolicitud = CorreccionSolicitud::findOrFail($request["id"]);
            return response(new CorreccionSolicitudResource($correccionSolicitud), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la solicitud de correccion'
            ], 500);
            //
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatecorreccionSolicitudRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            //return json_encode($data);
            $correccionSolicitud = CorreccionSolicitud::findOrFail($id);
            $correccionSolicitud->update($data);
            $correccionSolicitud->save();
            return response(new CorreccionSolicitudResource($correccionSolicitud), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la solicitud'
            ], 500);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CorreccionSolicitud $correccionSolicitud, string $id)
    {
        //return json_encode($correccionSolicitud);
        try {
            $solicitud = CorreccionSolicitud::findOrFail($id);
            
            if($solicitud->fecha_correccion == null){
                $solicitud->delete();
                return response("Operador eliminado con exito",200);
            }
            elseif($solicitud->fecha_correccion != null){
                return response()->json([
                    'error' => 'No se puede borrar una solicitud finalizada'
                ], 500);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la solicitud'.$e
            ], 500);
        }
        //
    }
}
