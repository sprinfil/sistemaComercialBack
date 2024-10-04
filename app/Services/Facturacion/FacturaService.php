<?php
namespace App\Services\Facturacion;

use App\Http\Resources\FacturaResource;
use App\Models\Factura;
use App\Models\Periodo;
use COM;
use Exception;
use Illuminate\Http\Client\Request;

class FacturaService{


    public function indexFacturaService()
    {
       
       try {
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
       } catch (Exception $ex) {

        return response()->json([
            'message' => 'No se encontraron registros de facturas.'
        ], 200);
       }
        

    }

    public function storeFacturaService(array $data)
    {
        try {       
            if ($data) {
                $factura = Factura::create($data);
                return response(new FacturaResource($factura), 201);
            }
        } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar la factura.'
             ], 200);
        }              
    }

    

    public function updateFacturaService(array $data, string $id)
    {
               //pendiente metodo de refacturacion
        try {            
                                  
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error durante la refacturacion.'
            ], 200);
        }        
              
    }

    public function showFacturaService(string $id)
    {
        try {
            $factura = Factura::findOrFail($id);
            return response(new FacturaResource($factura), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de la factura.'
            ], 500);
        }
    }

    public function facturaPorTomaService(string $idToma)
    {      //obtiene la factura mas reciente de la toma
        try {
           $factura = Factura::where('id_toma',$idToma)->latest()->first();         
            return response(new FacturaResource($factura), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se encontraron facturas activas'
            ], 500);
        }
    }
    public function storePeriodo($per){
        $periodo=Periodo::create($per);
        return $periodo;
    }

}