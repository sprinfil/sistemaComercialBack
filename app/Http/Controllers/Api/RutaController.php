<?php

namespace App\Http\Controllers\Api;

use mysqli;
use Exception;
use App\Models\Ruta;
use App\Models\Toma;
use App\Models\Libro;
use App\Models\Punto;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\RutaResource;
use App\Models\AsignacionGeografica;
use App\Http\Resources\LibroResource;
use App\Http\Requests\StoreRutaRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UpdateRutaRequest;
use App\Http\Resources\RutaSimplificado;
use App\Models\Secuencia;
use App\Services\Facturacion\RutaService;
use App\Services\SecuenciaService;
use Faker\Core\Coordinates;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

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
    public function secuencias()
    {
        //pediente asignar permisos
        $secuencias=RutaSimplificado::collection(
            Ruta::with(['Libros.tomas','Libros.secuencias.ordenesSecuencia.toma:id,codigo_toma,posicion,clave_catastral','Libros.secuencias.ordenesSecuenciaCero.toma:id,codigo_toma,posicion,clave_catastral'])->orderby("id")->get()
        );
        return $secuencias;
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
        DB::beginTransaction();
        $data = $request["data"];
        foreach ($data as $nombre_ruta => $ruta_data) {
            $ruta = new Ruta();
            $ruta->nombre = $nombre_ruta;
            $ruta->save();
            foreach ($ruta_data as $key => $libro_data_1) {
                foreach ($libro_data_1 as $nombre_libro => $libro_data_2) {

                    $libro = new Libro();
                    $libro->id_ruta = $ruta->id;
                    $libro->nombre = $nombre_libro;

                    $points = [];
                    foreach ($libro_data_2 as $punto_data) {
                        $points[] = new Point( /* latitud */$punto_data[1], /*longitud*/ $punto_data[0]);
                    }
                    $lineString = new LineString($points);
                    $polygon = new Polygon([$lineString]);

                    $libro->polygon = $polygon;
                    $libro->save();
                    $secuencia_input=[
                        "tipo_secuencia"=>"padre",
                        "id_libro"=>$libro->id,
                    ];
                    $secuencia=(new SecuenciaService())->store($secuencia_input, null);
                    $orden=[];
                    $i=1;
                    $tomasDentroDelPoligono = Toma::whereWithin('posicion', $libro->polygon)->get();
                    foreach ($tomasDentroDelPoligono as $toma) {
                        $toma->id_libro = $libro->id;
                        $toma->save();
                        if ($toma->estatus=="activa" && $toma->c_agua!=null && $toma->c_agua!=0){
                            $orden[]=[
                                "id_toma"=>$toma->id,
                                "numero_secuencia"=>$i++,
                            ];
                        }
                       
                    }
                    $Secuencia_orden=(new SecuenciaService())->SecuenciaOrdenStore($secuencia, $orden);
                }
            }
        }
        //Secuencia::insert([$secuencia]);
        DB::commit();
    }

    public function masive_store_deprecated(Request $request)
    {
        $data = $request["data"];
        foreach ($data as $nombre_ruta => $ruta_data) {
            $ruta = new Ruta();
            $ruta->nombre = $nombre_ruta;
            $ruta->save();
            foreach ($ruta_data as $key => $libro_data_1) {
                foreach ($libro_data_1 as $nombre_libro => $libro_data_2) {
                    $libro = new Libro();
                    $libro->id_ruta = $ruta->id;
                    $libro->nombre = $nombre_libro;
                    $libro->save();

                    $asignacion_geografica = new AsignacionGeografica();
                    $asignacion_geografica->id_modelo = $libro->id;
                    $asignacion_geografica->modelo = "libro";
                    $asignacion_geografica->estatus = "activo";
                    $asignacion_geografica->save();

                    foreach ($libro_data_2 as $punto_data) {
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
    public function masive_polygon_delete()
    {
        Libro::withTrashed()->forceDelete();
        Ruta::withTrashed()->forceDelete();
        Secuencia::withTrashed()->forceDelete();
    }

    public function masive_polygon_delete_deprecated()
    {
        $rutas = Ruta::all();
        foreach ($rutas as $ruta) {
            $libros = Libro::where("id_ruta", $ruta->id)->get();
            foreach ($libros as $libro) {
                $asignacion_geografica = AsignacionGeografica::where("modelo", "libro")
                    ->where("id_modelo", $libro->id)->first();
                if ($asignacion_geografica) {
                    foreach ($asignacion_geografica->puntos as $punto) {
                        $punto->forceDelete();
                    }
                    $asignacion_geografica->forceDelete();
                }
                $libro->forceDelete();
            }
            $ruta->forceDelete();
        }
    }

    public function create_polygon()
    {
        $polygon = new Polygon([
            new LineString([
                new Point(12.455363273620605, 41.90746728266806),
                new Point(12.457906007766724, 41.90000118654431),
                new Point(12.458517551422117, 41.90281205461268),
                new Point(12.457584142684937, 41.903107507989986),
                new Point(12.457734346389769, 41.905918239316286),
                new Point(12.45572805404663, 41.90637337450963),
                new Point(12.455363273620605, 41.90746728266806),
            ])
        ]);

        /*
             $asignacion_geografica = AsignacionGeografica::find(1);
      
           $asignacion_geografica->polygon = $polygon;
             $asignacion_geografica->save();
      
        echo json_encode($asignacion_geografica);
        return;
        */

        $libro = Libro::find(21);
        echo json_encode(new LibroResource($libro));
    }

    public function export_geojson()
    {
        $libros = Libro::all();

        $features = $libros->map(function ($libro) {

            $coordinates = json_encode($libro->polygon);
            $array_coordinates = json_decode($coordinates, true);
            $coordinates = $array_coordinates["coordinates"];

            return [
                'type' => 'Feature',
                'properties' => [
                    'name' => $libro->nombre,
                ],
                'geometry' => [
                    "type" => "MultiPolygon",
                    "coordinates" => [$coordinates],
                ],
            ];
        });

        $data = [
            "type" => "FeatureCollection",
            "name" =>  "libroslapaz",
            "features" => $features
        ];
        
        $geojson = json_encode($data, JSON_PRETTY_PRINT);

        $fileName = 'libroslapaz.geojson';

        Storage::disk('local')->put($fileName, $geojson);

        return response()->download(storage_path("app/{$fileName}"))->deleteFileAfterSend(true);
    }

    public function librosPorRuta(string $id)
    {
        try {
            DB::beginTransaction();
            $libros = (new RutaService())->librosPorRutaService($id);
            DB::commit();
            return $libros;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al relizar la busqueda de libros.'
            ], 500);
        }
    }



}
