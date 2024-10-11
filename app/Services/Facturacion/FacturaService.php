<?php
namespace App\Services\Facturacion;

use App\Http\Resources\FacturaResource;
use App\Jobs\FacturacionTomaJob;
use App\Models\Consumo;
use App\Models\Factura;
use App\Models\Libro;
use App\Models\Periodo;
use App\Models\Ruta;
use App\Models\Tarifa;
use App\Models\TarifaServiciosDetalle;
use App\Models\Toma;
use COM;
use Database\Seeders\LibroSeeder;
use ErrorException;
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

    public function storeFacturaService(array $periodos)
    {
        //cHECAR SI LAS TOMAS YA TIENEN UNA FACTURA VIGENTE
        $id_periodos=array_column($periodos,"id_periodo");
        $periodosTomas=Periodo::with(['tieneRutas'=>function($q){ ///consultas relacionadas procesadas
            $q->with(['Libros'=>function($q2){
                $q2->with(['tomas'=>function ($q3){
                    $q3->where('estatus','activa')->orWhere('estatus','limitado');
                }]);
            }]);
        }])->whereIn('id',$id_periodos)->where('estatus','activo')->get();
        foreach ($periodosTomas as $periodo){

        }
        $periodosFactura=[];

  
        return $periodosTomas;             
    }

    public function facturarIndividual($toma,$tarifaToma,$periodo,$consumo){
      if ($toma['c_agua']){
        return $toma;
      }

    }

    public function facturaracionPorToma($id_toma){
        $toma=Toma::find($id_toma);
        $libro= $toma->libro;
        $ruta=$libro->tieneRuta;
        $periodo=$ruta->PeriodoActivo;
        $tarifa=$periodo->tarifa;
        $consumo=Consumo::where('id_periodo',$periodo->id)->where('id_toma',$toma->id)->first();
        //dispatch(new FacturacionTomaJob($toma));
        $tarifaToma=Tarifa::servicioToma($tarifa->id,$toma->id_tipo_toma,$consumo->consumo);
        $facturaToma=($this->facturarIndividual($toma,$tarifaToma,$periodo,$consumo));
        return $facturaToma;
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
        $factura = Factura::where('id_toma',$idToma)->latest()->first();         
        return response(new FacturaResource($factura), 200);
    }


}