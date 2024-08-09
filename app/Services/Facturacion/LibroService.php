<?php
namespace App\Services\Facturacion;

use App\Http\Resources\LibroResource;
use App\Models\AsignacionGeografica;
use App\Models\Libro;
use COM;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use Exception;
use Illuminate\Http\Client\Request;

class LibroService{


    public function indexLibroService()
    {      
       try {
        return LibroResource::collection(
            Libro::all()
        );
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron registros de libros.'
        ], 200);
       }       
    }

    public function storeLibroService(array $data, string $nombre, string $id_ruta)
    {
        try {       
               //Busca por registros eliminados
               $libros = Libro::withTrashed()->where('id_ruta', $$id_ruta)->where('nombre', $nombre)->first();
    
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
                   
        } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar el libro en el catalogo.'
             ], 200);
        }              
    }

    public function store_deprecatedLibroService(array $data, $nombre, $id_ruta)
    {
        //Pendiente de permiso
        try {

            //Busca por registros eliminados
            $libros = Libro::withTrashed()->where('id_ruta', $id_ruta)->where('nombre', $nombre)->first();
    
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


    public function updateLibroService(array $data, string $id, string $nombre)
    {
               
        try {            
            $rutaAsociada = Libro::select('id_ruta')->where('id', $id)->first();
            $listaLibros = Libro::select('nombre')->where('id_ruta', $rutaAsociada->id_ruta)->get();

            foreach ($listaLibros as $librosReg) {

                if ($librosReg->nombre == $nombre) {
                return response()->json([
                    'error' => 'Esten nombre ya se encuentra asociado a esta ruta'
                 ], 200);
                }
            }

            $libro = Libro::findOrFail($id);
            $libro->update($data);
            $libro->save();
            return response(new LibroResource($libro), 200);                     
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error durante la modificacion del libro.'
            ], 200);
        }        
              
    }

    public function showLibroService(string $id)
    {
        try {
            $libro = Libro::findOrFail($id);
            return response(new LibroResource($libro), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda del libro.'
            ], 500);
        }
    }

    public function destroyLibroService(string $id)
    {
        
        try {
            $libro = Libro::findOrFail($id);
            $libro->delete();
            return response("El libro se ha eliminado con exito",200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se borro el libro del catalogo.'
            ], 500);
        }
    }

    public function restaurarDatoLibroService (string $id)
    {
        try {
            $libro = Libro::withTrashed()->findOrFail($id);
            //Condicion para verificar si el registro esta eliminado
            if ($libro->trashed()) {
               //Restaura el registro
               $libro->restore();
               return response()->json(['message' => 'El libro ha sido restaurado' , 200]);
           }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se restauro el libro.'
            ], 500);
        }
        
    }

    public function update_polygonLibroServicio(array $new_points, string $libro_id){
        $libro = Libro::find($libro_id);
       // $new_points = $request["puntos"];

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