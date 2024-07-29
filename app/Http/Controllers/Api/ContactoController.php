<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Contacto;
use App\Http\Requests\StoreContactoRequest;
use App\Http\Requests\UpdateContactoRequest;
use App\Http\Resources\ContactoResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ContactoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return ContactoResource::collection(
            Contacto::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactoRequest $request)
    {
        //$this->authorize('create', ConceptoCatalogo::class); pendiente permisos

        try {

            //Valida el store
            $data = $request->validated();                     
            $contacto = Contacto::create($data);
            return new ContactoResource($contacto);
                                     
        } catch (ModelNotFoundException $e) {
                return response()->json([
                    'error' => 'Ocurrio un error al registrar el contacto'
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
            $contacto = Contacto::findOrFail($id);
            return response(new ContactoResource($contacto), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se ha encontrado el contacto'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateContactoRequest $request,  string $id)
    {
        //$this->authorize('update', GiroComercialCatalogo::class); pendiente permiso
        try {
            $data = $request->validated();
            $contacto = Contacto::findOrFail($id);
            $contacto->update($data);
            $contacto->save();
            return response(new ContactoResource($contacto), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrio un error al editar el contacto'
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
            $contacto = Contacto::findOrFail($id);
            $contacto->delete();
            return response("El contacto se ha eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Ocurrio un error al eliminar el contacto'
            ], 500);
        }
    }
}
