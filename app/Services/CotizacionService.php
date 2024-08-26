<?php
namespace App\Services;

use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\Contrato;
use App\Models\Cotizacion;
use App\Models\Toma;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Collection;

class CotizacionService{
    public function CargoContratos($contratos){
        $cargo=new Collection();
        $fecha=Carbon::now();
        $fecha->format('Y-m-d');
      
        foreach ($contratos as $contrato){
            $monto=$contrato['montoDetalle']+$contrato['monto'];
            $cargo->push(Cargo::create([
                'id_concepto' => $contrato['id_concepto'],
                'concepto' => $contrato['concepto'],
                'id_origen' => $contrato['id_contrato'],
                'modelo_origen' => "contrato",
                'id_dueno' => $contrato['id_toma'],
                'modelo_dueno' => "toma",
                'monto' => $monto,
                'fecha_cargo' => $fecha,
            ]));
        }
       return $cargo;
    }
    public function TomaCotizacion($cotizacion): Toma{
        $toma=Cotizacion::find($cotizacion);
        return $toma->TomaCotizada();
    }
    
    public function TarifaPorContrato($cotizaciones){
        $concepto=new Collection();
        $i=0;
        foreach ($cotizaciones as $cot){
            
            $cotizacion=Cotizacion::find($cot);
            $contrato=$cotizacion->contrato;
            $concepto->push($contrato->tarifaContrato());
            $concepto[$i]['id_cotizacion']=$cotizacion['id'];
            $concepto[$i]['id_toma']=$cotizacion->TomaCotizada->id;
            $concepto[$i]['id_contrato']=$contrato->id;
            $concepto[$i]['concepto']=$contrato->conceptoContrato()->nombre;
            $i++;
        }
        $conceptos= $concepto->unique('id');
        return $conceptos;
        //return $concepto;
    }
}