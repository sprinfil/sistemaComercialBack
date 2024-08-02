<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Http\Resources\FacturaResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$this->authorize('viewAny', GiroComercialCatalogo::class); pendiente de permisos
        $idReciente = Factura::max('id');

        if($idReciente > 501){
            $idReciente = $idReciente - 500;

            return FacturaResource::collection(
                Factura::where('id', '>', $idReciente)->get()
            );

        }
        if ($idReciente < 501) {
            $idReciente = 0;

            return FacturaResource::collection(
                Factura::where('id', '>', $idReciente)->get()
            );
           
        }
        else{
            return response()->json([
                'message' => 'Ocurrio un error al realizar la busqueda',
            ], 200);
        }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacturaRequest $request)
    {
        //$this->authorize('create', GiroComercialCatalogo::class); pendiente de permisos
        
        $data = $request->validated();
       
       try {
        if ($data) {

            $factura = Factura::create($data);
            return response(new FacturaResource($factura), 201);
        }
       } catch (ModelNotFoundException $e) {
        return response()->json([
            'error' => 'Ocurrio un error al guardar la factura'
        ], 500);
       }
       
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        
        try {
            $factura = Factura::findOrFail($id);
            return response(new FacturaResource($factura), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'Ocurrio un error al buscar la factura'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFacturaRequest $request, Factura $factura)
    {
        // pendiente permisos y metodo de refacturacion
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Factura $factura)
    {
        //
    }

    public function facturaPorToma(string $idToma)
    {
       
        try {
           $factura = Factura::where('id_toma',$idToma)->latest()->first();         
            return response(new FacturaResource($factura), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se encontraron facturas activas'
            ], 500);
        }
    }
}
