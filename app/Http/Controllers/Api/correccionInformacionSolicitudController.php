<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\correccionInformacionSolicitud;
use App\Http\Requests\StorecorreccionInformacionSolicitudRequest;
use App\Http\Requests\UpdatecorreccionInformacionSolicitudRequest;
use App\Http\Resources\correccionInformacionSolicitudResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class correccionInformacionSolicitudController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return correccionInformacionSolicitudResource::collection(
            correccionInformacionSolicitud::all()
        );
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorecorreccionInformacionSolicitudRequest $request)
    {
        try{
            $data = $request->validated();
            $correccionInformacionSolicitud = correccionInformacionSolicitud::create($data);
            return response(new correccionInformacionSolicitudResource($correccionInformacionSolicitud), 201);
            
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la solicitud de correccion'
            ], 500);
        }
         
    }

    /**
     * Display the specified resource.
     */
    public function show(correccionInformacionSolicitud $correccionInformacionSolicitud, Request $request)
    {
        //Falta consulta especifica 
        try {
            $correccionInformacionSolicitud = correccionInformacionSolicitud::findOrFail($request["id"]);
            return response(new correccionInformacionSolicitudResource($correccionInformacionSolicitud), 200);
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
    public function update(UpdatecorreccionInformacionSolicitudRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            //return json_encode($data);
            $correccionInformacionSolicitud = correccionInformacionSolicitud::findOrFail($id);
            $correccionInformacionSolicitud->update($data);
            $correccionInformacionSolicitud->save();
            return response(new correccionInformacionSolicitudResource($correccionInformacionSolicitud), 200);
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
    public function destroy(correccionInformacionSolicitud $correccionInformacionSolicitud, string $id)
    {
        //return json_encode($correccionInformacionSolicitud);
        try {
            $solicitud = correccionInformacionSolicitud::findOrFail($id);
            
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
