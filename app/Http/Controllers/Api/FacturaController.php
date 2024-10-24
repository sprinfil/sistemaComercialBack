<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Factura;
use App\Http\Requests\StoreFacturaRequest;
use App\Http\Requests\UpdateFacturaRequest;
use App\Http\Resources\FacturaResource;
use App\Jobs\PeriodoFacturacionJob;
use App\Models\Toma;
use App\Services\AtencionUsuarios\ConvenioService;
use App\Services\AtencionUsuarios\DescuentoAsociadoService;
use App\Services\Caja\PagoService;
use App\Services\Facturacion\FacturaService;
use App\Services\Facturacion\indexFacturaServiceService;
use ErrorException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
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
    public function store(Request $request) //StoreFacturaRequest ///No se usa
    {
        //$this->authorize('create', GiroComercialCatalogo::class); pendiente de permisos        
        try{
            DB::beginTransaction();
            $data=$request->all()['periodos'];
            $facturas = (new FacturaService())->storeFacturaPeriodo($data);
            ///TO DO Recargos
            ///TO DO Cargar Letras
            DB::commit();
            return response()->json(["facturas"=>$facturas],200);
        }
        catch(Exception | ErrorException $ex){
            DB::rollBack();
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de peticion. ".$ex->getMessage()],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }
        }
        /*
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
        */            
    }
    public function storeToma($id_toma){
        try{
            DB::beginTransaction();
            $toma=Toma::find($id_toma);
            $facturas = (new FacturaService())->facturaracionPorToma($toma);
            
            DB::commit(); ///probar
            return response()->json(["facturas"=>$facturas[0],"cargos"=>$facturas[1],"Recargos"=>$facturas[2]],200);
        }
        catch(Exception | ErrorException $ex){
            DB::rollBack();
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de peticion. ".$ex->getMessage()],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }
        }
    }
    public function storePeriodo(Request $request){
        try{
            DB::beginTransaction();
            $data=$request['periodos'];
            (new FacturaService())->storeFacturaPeriodo($data);
            //$facturaToma=dispatch(new PeriodoFacturacionJob($data))->onQueue('facturaPeriodos');//colas
            DB::commit();
            //return response()->json(["facturas"=>$facturas[0], "cargos"=>$facturas[1],"Recargos"=>$facturas[2]],200);
            return response()->json(["message"=>"Facturacion iniciada"],200); // con colas
        }
        catch(Exception | ErrorException $ex){
            DB::rollBack();
            $clase= get_class($ex);
            if ($clase=="ErrorException"){
                return response()->json(["error"=>"Error de peticion. ".$ex],400);
            }
            else{
                return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
            }
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
           return response()->json(["toma"=>$factura[0],"saldo"=>$factura[1]],200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se encontraron facturas activas '. $ex
            ], 500);
        }
    }
    public function refacturarToma(Request $request){ ///
        try {
            DB::beginTransaction();
            $tomas=$request->all()['tomas'];
            $facturas = (new FacturaService())->Refacturacion($tomas[0]);   
            
            DB::commit();      
            return response()->json(["facturas"=>new FacturaResource($facturas[0]),"cargos"=>$facturas[1],"Recargos"=>$facturas[2],"cargos_viejos"=>$facturas[3]],200);
         } catch (Exception $ex) {
             DB::rollBack();
             $clase= get_class($ex);
             if ($clase=="ErrorException"){
                 return response()->json(["error"=>"Error de peticion. ".$ex->getMessage()],400);
             }
             else{
                 return response()->json(["error"=>"Error de servidor: ".$ex->getMessage()],500);
             }
         }
    }

}
