<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Ruta;
use App\Models\Libro;
use App\Models\Punto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RutaResource;
use App\Models\AsignacionGeografica;
use App\Http\Requests\StoreRutaRequest;
use App\Http\Requests\UpdateRutaRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RutaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //pediente asignar permisos
        return RutaResource::collection(
            Ruta::orderby("id")->get()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRutaRequest $request)
    {
        //$this->authorize('create', ConceptoCatalogo::class); pendiente permisos

        try {

            //Valida el store
            $data = $request->validated();

            //Busca por registros eliminados
            $rutaCatalogo = Ruta::withTrashed()->where('nombre', $request->input('nombre'))->first();

            //Validacion en caso de registro duplicado
            if ($rutaCatalogo) {
                if ($rutaCatalogo->trashed()) {
                    return response()->json([
                        'message' => 'La ruta ya existe pero ha sido eliminada. ¿Desea restaurarla?',
                        'restore' => true,
                        'ruta_id' => $rutaCatalogo->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'La ruta ya existe.',
                    'restore' => false
                ], 200);
            }

            //Si el dato no existe lo crea
            if (!$rutaCatalogo) {
                $rutaCatalogo = Ruta::create($data);
                return new RutaResource($rutaCatalogo);
            }
            //

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo añadir la ruta'
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
            $ruta = Ruta::findOrFail($id);
            return response(new RutaResource($ruta), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la ruta'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRutaRequest $request,  string $id)
    {
        //$this->authorize('update', GiroComercialCatalogo::class); pendiente permiso
        try {
            $data = $request->validated();
            $ruta = Ruta::findOrFail($id);
            $ruta->update($data);
            $ruta->save();
            return response(new RutaResource($ruta), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la ruta'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //$this->authorize('delete', GiroComercialCatalogo::class); pendiente permiso
        try {
            $ruta = Ruta::findOrFail($id);
            $ruta->delete();
            return response("La ruta se ha eliminado con exito", 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar la ruta'
            ], 500);
        }
    }

    public function restaurarRuta(Ruta $ruta, Request $request)
    {
        //Pendiente permiso
        try {
            $ruta = Ruta::withTrashed()->findOrFail($request->id);
            //Condicion para verificar si el registro esta eliminado
            if ($ruta->trashed()) {
                //Restaura el registro
                $ruta->restore();
                return response()->json(['message' => 'La ruta ha sido restaurado', 200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Ocurrio un error al restaurar la ruta'
            ], 500);
        }
    }

    public function masive_store(Request $request)
    {
        $data = $request["data"];
        foreach ($data as $nombre_ruta => $ruta_data) {
             $ruta = new Ruta();
                $ruta->nombre = $nombre_ruta;
                $ruta->save();
            foreach ($ruta_data as $key => $libro_data_1) {
                foreach($libro_data_1 as $nombre_libro => $libro_data_2){
                        $libro = new Libro();
                        $libro->id_ruta = $ruta->id;
                        $libro->nombre = $nombre_libro;
                        $libro->save();

                        $asignacion_geografica = new AsignacionGeografica();
                        $asignacion_geografica->id_modelo = $libro->id;
                        $asignacion_geografica->modelo = "libro";
                        $asignacion_geografica->estatus = "activo";
                        $asignacion_geografica->save();

                    foreach($libro_data_2 as $punto_data){
                        $punto = new Punto();
                        $punto->id_asignacion_geografica = $asignacion_geografica->id;
                        $punto->latitud = $punto_data[1];
                        $punto->longitud = $punto_data[0];
                        $punto->save();
                    }
                }
            }
            
        }
    }

    public function masive_polygon_delete(){
        $rutas = Ruta::all();
        foreach($rutas as $ruta){
            $libros = Libro::where("id_ruta", $ruta->id)->get();
            foreach($libros as $libro){
                $asignacion_geografica = AsignacionGeografica::where("modelo", "libro")
                ->where("id_modelo", $libro->id)->first();
                foreach($asignacion_geografica->puntos as $punto){
                    $punto->forceDelete();
                }
                $asignacion_geografica->forceDelete();
                $libro->forceDelete();
            }
            $ruta->forceDelete();
        }
    }
}
