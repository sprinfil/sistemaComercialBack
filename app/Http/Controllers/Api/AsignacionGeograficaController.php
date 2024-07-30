<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AsignacionGeografica;
use App\Http\Requests\StoreAsignacionGeograficaRequest;
use App\Http\Requests\UpdateAsignacionGeograficaRequest;
use App\Http\Resources\AsignacionGeograficaResource;
use App\Models\Libro;
use App\Models\Ruta;
use App\Models\Toma;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AsignacionGeograficaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //pediente asignar permisos correccion

        $asignacionGeograficaIndex = AsignacionGeografica::select('latitud','longitud')
        ->where('estatus','activo')->get();
        return $asignacionGeograficaIndex;

        return AsignacionGeograficaResource::collection(
            AsignacionGeografica::all()->where('estado','activo')
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAsignacionGeograficaRequest $request)
    {
        //Pendiente de permiso
        
        try {

            //Valida el store
            $data = $request->validated();
            //Busca por registros eliminados
            $asignacionGeografica = AsignacionGeografica::where('modelo', $request->input('modelo'))->where('id_modelo', $request->input('id_modelo'))->first();
    
            //Validacion en caso de registro duplicado
            if ($asignacionGeografica) {
                return response()->json([
                    'message' => 'Ya existe una asignacion geografica asociada a este registro.',
                ], 200);
            }
    
            //Si el dato no existe lo crea
            if(!$asignacionGeografica)
            {
                $asignacionGeografica = AsignacionGeografica::create($data);
                return new AsignacionGeograficaResource($asignacionGeografica);
            }
            //
                
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'No se pudo añadir la asignacion geografica'
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

            $asignacionGeografica = AsignacionGeografica::findOrFail($id);
            if ($asignacionGeografica->estatus == "activo") {
                return response(new AsignacionGeograficaResource($asignacionGeografica), 200);
            }else{
                return response()->json([
                    'error' => 'No se pudo encontrar la asignacion geografica'
            ], 500);
            }
            

        } catch (ModelNotFoundException $e) {
            return response()->json([
                    'error' => 'No se pudo encontrar la asignacion geografica'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAsignacionGeograficaRequest $request, string $id)
    {
         //$this->authorize('update', GiroComercialCatalogo::class); pendiente permiso, Pendiente validar que no se pueda desactivar a mitad de recorrido de lectura
        
         try {
            $data = $request->validated();
            $asignacionGeografica = AsignacionGeografica::findOrFail($id);
            $asignacionGeografica->update($data);
            $asignacionGeografica->save();
            return response(new asignacionGeograficaResource($asignacionGeografica), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Error al editar la asignacion geografica'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AsignacionGeografica $asignacionGeografica, string $id)
    {
        //Pendiente permisos
        try {
            $asignacionGeografica = AsignacionGeografica::findOrFail($id);
            $asignacionGeografica->delete();
            return response("La asignacion geografica se ha eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Ocurrio un error al eliminar la asignacion geografica'
            ], 500);
        }
    }

    //Pendiente consultas relacionadas con toma, libro y ruta par alos poligonos
    public function asignaciongeograficaToma($id)
    {
        try{
            $asignacion = Toma::findOrFail($id);
            $puntos = $asignacion->asignacionGeografica;
            if($puntos){
                $puntos->puntos;
            }
            if($puntos){
                return $puntos;
            }
            return response()->json([
                'error' => 'No hay puntos'
            ], 400);
        } catch(Exception $ex){
            return response()->json([
                'error' => 'Error'. $ex
            ], 500);
        }
        
    }

    public function asignaciongeograficaLibro($id)
    {
        try{
            $asignacion = Libro::findOrFail($id);
            $puntos = $asignacion->asignacionGeografica;
            if($puntos){
                $puntos->puntos;
            }
            if($puntos){
                return $puntos;
            }
            return response()->json([
                'error' => 'No hay puntos'
            ], 400);
        } catch(Exception $ex){
            return response()->json([
                'error' => 'Error'. $ex
            ], 500);
        }
    }

    public function asignaciongeograficaRuta($id)
    {
        try{
            $asignacion = Ruta::findOrFail($id);
            $puntos = $asignacion->asignacionGeografica;
            if($puntos){
                $puntos->puntos;
            }
            if($puntos){
                return $puntos;
            }
            return response()->json([
                'error' => 'No hay puntos'
            ], 400);
        } catch(Exception $ex){
            return response()->json([
                'error' => 'Error'. $ex
            ], 500);
        }
    }

    
}
