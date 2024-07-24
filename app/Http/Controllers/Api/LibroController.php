<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Libro;
use App\Http\Requests\StoreLibroRequest;
use App\Http\Requests\UpdateLibroRequest;
use App\Http\Resources\LibroResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

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
                $libros = Libro::create($data);
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
        //$this->authorize('update', GiroComercialCatalogo::class); pendiente permiso
        try {
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
        //$this->authorize('delete', GiroComercialCatalogo::class); pendiente permiso
        try {
            $libro = Libro::findOrFail($id);
            $libro->delete();
            return response("El libro se ha eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el libro'
            ], 500);
        }
    }

    public function restaurarRuta (Libro $ruta, Request $request)
    {
        //Pendiente permiso
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
}
