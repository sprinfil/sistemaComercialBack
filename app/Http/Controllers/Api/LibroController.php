<?php

namespace App\Http\Controllers\Api;

use Exception;
use App\Models\Libro;
use App\Models\Punto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\AsignacionGeografica;
use App\Http\Resources\LibroResource;
use App\Http\Requests\StoreLibroRequest;
use App\Http\Requests\UpdateLibroRequest;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //pediente asignar permisos
        return LibroResource::collection(
            Libro::all()
        );
    }

        /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLibroRequest $request)
    {
        //Pendiente de permiso
        
        try {

            //Valida el store
            $data = $request->validated();
            //Busca por registros eliminados
            $libros = Libro::withTrashed()->where('id_ruta', $request->input('id_ruta'))->where('nombre', $request->input('nombre'))->first();
    
            //Validacion en caso de registro duplicado
            if ($libros) {
                if ($libros->trashed()) {
                    return response()->json([
                        'message' => 'El libro ya existe en esta ruta pero ha sido eliminado. ¿Desea restaurarlo?',
                        'restore' => true,
                        'ruta_id' => $libros->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'El libro ya existe en esta ruta.',
                    'restore' => false
                ], 200);
            }
    
            //Si el dato no existe lo crea
            if(!$libros)
            {
                $libro = Libro::create($data);

                $polygon = new Polygon([
                    new LineString([
                        new Point(24.1277, -110.3033),
                        new Point(24.1343, -110.3033),
                        new Point(24.1343, -110.2967),
                        new Point(24.1277, -110.3033),
                    ])
                ]);

                $libro->polygon = $polygon;
                $libro->save();

                return new LibroResource($libro);
            }
            //
                
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'No se pudo añadir el libro'
                ], 500);
            }      
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_deprecated(StoreLibroRequest $request)
    {
        //Pendiente de permiso
        
        try {

            //Valida el store
            $data = $request->validated();
            //Busca por registros eliminados
            $libros = Libro::withTrashed()->where('id_ruta', $request->input('id_ruta'))->where('nombre', $request->input('nombre'))->first();
    
            //Validacion en caso de registro duplicado
            if ($libros) {
                if ($libros->trashed()) {
                    return response()->json([
                        'message' => 'El libro ya existe en esta ruta pero ha sido eliminado. ¿Desea restaurarlo?',
                        'restore' => true,
                        'ruta_id' => $libros->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'El libro ya existe en esta ruta.',
                    'restore' => false
                ], 200);
            }
    
            //Si el dato no existe lo crea
            if(!$libros)
            {
                $libros = Libro::create($data);

                $asignacionGeografica = new AsignacionGeografica();
                $asignacionGeografica->modelo = "libro";
                $asignacionGeografica->id_modelo = $libros->id;
                $asignacionGeografica->estatus = "activo";
                $asignacionGeografica->save();

                $default_coords = [
                    [
                        "latitud"=>24.1277,
                        "longitud"=>-110.3033
                    ],
                    [
                        "latitud"=>24.1343,
                        "longitud"=>-110.3033
                    ],
                    [
                        "latitud"=>24.1343,
                        "longitud"=>-110.2967
                    ],
                    [
                        "latitud"=>24.1277,
                        "longitud"=>-110.2967
                    ],
                ];

                foreach($default_coords as $coords){
                    $punto = new Punto();
                    $punto->id_asignacion_geografica = $asignacionGeografica->id;
                    $punto->latitud = $coords["latitud"];
                    $punto->longitud = $coords["longitud"];
                    $punto->save();
                }

                return new LibroResource($libros);
            }
            //
                
            } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'No se pudo añadir el libro'
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

            $libro = Libro::findOrFail($id);
            return response(new LibroResource($libro), 200);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                    'error' => 'No se pudo encontrar el libro'
            ], 500);
        }
      
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLibroRequest $request,  string $id)
    {
        //$this->authorize('update', GiroComercialCatalogo::class); pendiente permiso withTrashed()
        

        try {

            $rutaAsociada = Libro::select('id_ruta')->where('id', $id)->first();
            $listaLibros = Libro::select('nombre')->where('id_ruta', $rutaAsociada->id_ruta)->get();

            foreach ($listaLibros as $librosReg) {

                if ($librosReg->nombre == $request->nombre) {
                return response()->json([
                    'error' => 'Esten nombre ya se encuentra asociado a esta ruta'
                 ], 200);
                }
            }

            $data = $request->validated();
            $libro = Libro::findOrFail($id);
            $libro->update($data);
            $libro->save();
            return response(new LibroResource($libro), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el libro'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //$this->authorize('delete', GiroComercialCatalogo::class); pendiente permiso, pendiente que no se pueda borrar un libro asociado a tomas
        try {
            $libro = Libro::findOrFail($id);
            $libro->delete();
            return response("El libro se ha eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Ocurrio un error al borrar el libro'
            ], 500);
        }
    }

    public function restaurarLibro (Libro $libro, Request $request)
    {
        //Pendiente permiso, Pendiente validar las implicaciones de restaurar un libro
        try {
            $libro = Libro::withTrashed()->findOrFail($request->id);
            //Condicion para verificar si el registro esta eliminado
            if ($libro->trashed()) {
               //Restaura el registro
               $libro->restore();
               return response()->json(['message' => 'El libro ha sido restaurado' , 200]);
           }
           
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Ocurrio un error al restaurar el libro'
            ], 500);
        }
        
    }

    public function update_polygon(Request $request, $libro_id){
        $libro = Libro::find($libro_id);
        $new_points = $request["puntos"];

        $points = [];
        
        foreach ($new_points as $punto_data) {
            $points[] = new Point( /* latitud */$punto_data["lat"], /*longitud*/$punto_data["lng"]);
        }
        $lineString = new LineString($points);
        $polygon = new Polygon([$lineString]);

        $libro->polygon = $polygon;
        $libro->save();
    }
}
