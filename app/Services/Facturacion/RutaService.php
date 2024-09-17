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

class RutaService{

    public function librosPorRutaService(string $id)
    {
        try {
            $libros = Libro::select('id','nombre')
            ->where('id_ruta',$id)
            ->get();
            if ($libros) {
                return json_encode($libros);
            }
            else {
                return response()->json([
                    'error' => 'No existen libros asociados a esta ruta.'
                ]);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al relizar la busqueda de libros.'
            ], 500);
        }
    }
}