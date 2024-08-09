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
use App\Services\Facturacion\LibroService;
use MatanYadaev\EloquentSpatial\Objects\Point;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Objects\LineString;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class LibroController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //pediente asignar permisos
        try {
            DB::beginTransaction();
            $libro = (new LibroService())->indexLibroService();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'No se encontraron registros de libros.'
            ], 200);
        }
       
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
            $nombre = $request->nombre;
            $id_ruta = $request->id_ruta;
            DB::beginTransaction();
            $libro = (new LibroService())->storeLibroService($data, $nombre, $id_ruta);
            DB::commit();
            return $libro;
        } catch (Exception $ex) {
                DB::rollBack();
                return response()->json([
                    'error' => 'No se registro el libro'
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
            $nombre = $request->nombre;
            $id_ruta = $request->id_ruta;
            DB::beginTransaction();
            $libro = (new LibroService())->store_deprecatedLibroService($data,$nombre,$id_ruta);
            DB::commit();
            return $libro;
          
            } catch (Exception $ex) {
                DB::rollBack();
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
            DB::beginTransaction();
            $libro = (new LibroService())->showLibroService($id);
            DB::commit();
            return $libro;

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                    'error' => 'No se encontro el libro'
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

            $data = $request->validated();
            $nombre = $request->nombre;
            DB::beginTransaction();
            $libro = (new LibroService())->updateLibroService($data, $id, $nombre);
            DB::commit();
            return $libro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se edito el libro'
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
            DB::beginTransaction();
            $libro = (new LibroService())->destroyLibroService($id);
            DB::commit();
            return $libro;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al borrar el libro'
            ], 500);
        }
    }

    public function restaurarLibro (Libro $libro, Request $request)
    {
        //Pendiente permiso, Pendiente validar las implicaciones de restaurar un libro
        try {
            $id = $request->id;
            DB::beginTransaction();
           $libro = (new LibroService())->restaurarDatoLibroService($id);
           DB::commit();
           return $libro;
           
        } catch (ModelNotFoundException $ex) {
            DB::rollBack(); 
           return response()->json([
                'error' => 'Ocurrio un error al restaurar el libro'
            ], 500);
        }
        
    }

    public function update_polygon(Request $request, $libro_id){
        try {
          $new_points = $request["puntos"];
          DB::beginTransaction();
          $libro = (new LibroService())->update_polygonLibroServicio($new_points, $libro_id);
          DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se actualizo el poligono.'
            ], 500);
        }
    }
}
