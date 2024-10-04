<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Http\Resources\FacturaResource;
use App\Services\Facturacion\FacturaService;
use App\Services\Facturacion\indexFacturaServiceService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\DB;

class FacturaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //$this->authorize('viewAny', GiroComercialCatalogo::class); pendiente de permisos
        
       try {
        DB::beginTransaction();
        $factura = (new FacturaService())->indexFacturaService();
        DB::commit();
        return $factura;
       } catch (Exception $ex) {
        DB::rollBack();
        return response()->json([
            'message' => 'No se encontraron registros de facturas.'
        ], 200);
       }
        
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreFacturaRequest $request)
    {
        //$this->authorize('create', GiroComercialCatalogo::class); pendiente de permisos        
       try {
        $data = $request->validated();
        DB::beginTransaction();
        $factura = (new FacturaService())->storeFacturaService($data);
        DB::commit();
        return $factura;
       } catch (Exception $ex) {
        DB::rollBack();
        return response()->json([
            'error' => 'Ocurrio un error al registrar la factura.'
        ], 500);
       }              
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            DB::beginTransaction();
            $factura = (new FacturaService())->showFacturaService($id);
            DB::commit();
            return $factura;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de la factura.'
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
           DB::beginTransaction();
           $factura = (new FacturaService())->facturaPorTomaService($idToma);   
           DB::commit();      
           return $factura;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se encontraron facturas activas'
            ], 500);
        }
    }
    public function storePeriodo(Request $request){
        try{
            $data=$request->all();
            $periodo=(new FacturaService())->storePeriodo($data);
            return response()->json(["periodo"=>$periodo],200);
        }
        catch(Exception $ex){
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo crear un nuevo periodo '.$ex->getMessage()
            ], 500);
        }


    }
}
