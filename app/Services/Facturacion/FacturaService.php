<?php
namespace App\Services\Facturacion;

use App\Http\Resources\FacturaResource;
use App\Jobs\FacturacionTomaJob;
use App\Jobs\PeriodoFacturacionJob;
use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\Consumo;
use App\Models\Factura;
use App\Models\Libro;
use App\Models\Periodo;
use App\Models\Ruta;
use App\Models\Tarifa;
use App\Models\TarifaServiciosDetalle;
use App\Models\Toma;
use App\Services\AtencionUsuarios\ConvenioService;
use App\Services\AtencionUsuarios\DescuentoAsociadoService;
use App\Services\Caja\PagoService;
use Carbon\Carbon;
use COM;
use Database\Seeders\LibroSeeder;
use ErrorException;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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

    public function storeFacturaPeriodo(array $periodos) //facturacion del periodo
    {
        //cHECAR SI LAS TOMAS YA TIENEN UNA FACTURA VIGENTE
        $id_periodos=array_column($periodos,"id");
        /*
        $periodosTomas=Periodo::with(['tieneRutas'=>function($q){ ///consultas relacionadas procesadas
            $q->with(['Libros'=>function($q2){
                $q2->with(['tomas'=>function ($q3){
                    $q3->where('estatus','activa')->orWhere('estatus','limitado');
                }]);
            }]);
        }])->whereIn('id',$id_periodos)->where('estatus','activo')->get();
        */
        $periodosFactura=new Collection();
        $facturaCargos=new Collection();
        $RecargosCollection=new Collection();
        $periodosTomas=Periodo::with('tieneRutas.Libros.tomas:id,id_usuario,id_giro_comercial,id_libro,codigo_toma,id_tipo_toma,estatus,c_agua,c_alc,c_san','tieneRutas:id,nombre','tarifa')->whereIn('id',$id_periodos)->where('estatus','activo')->get();
        foreach ($periodosTomas as $periodo){

            $libros=$periodo['tieneRutas']['Libros'];
            $tarifa=$periodo['tarifa'];
            foreach ($libros as $libro){
                $tomas=$libro['tomas'];
            
                foreach ($tomas as $toma){
                    $ExisteFactura=Factura::where('id_periodo',$periodo['id'])->where('id_toma',$toma['id'])->first();
                    if ($ExisteFactura){
                        $ExistenCargos=Cargo::where('id_origen',$ExisteFactura['id'])->get();
                        if (count($ExistenCargos)!=0){
                            throw new ErrorException("No se puede facturar una toma con una facturación vigente dentro del mismo periodo");
                        }
                    }
                    $consumo=Consumo::where('id_periodo',$periodo->id)->where('id_toma',$toma->id)->where('estado','activo')->first();

          
                    if ($consumo){
                        $tarifaToma=Tarifa::servicioToma($tarifa->id,$toma->id_tipo_toma,$consumo->consumo);
                       
                  
                       //$facturaToma=($this->facturar($toma,$tarifaToma,$periodo,$consumo));
                        $facturaToma=dispatch(new PeriodoFacturacionJob($toma,$tarifaToma,$periodo,$consumo))->onQueue('facturaPeriodos');//colas
                        //$periodosFactura->push($facturaToma[0]);
                        //$facturaCargos->push($facturaToma[1]);
                        //$RecargosCollection->push($facturaToma[2]);
                       
                 
                        
                    }
                   
                }
            }
        }
        //return [$periodosFactura, $facturaCargos,$RecargosCollection ];
        //return [$periodosFactura, $facturaCargos,$RecargosCollection ];               
    }

    public function facturar($toma,$tarifaToma,$periodo,$consumo){
        $tarifa=$tarifaToma[0];
        if ($toma['estatus']=="activa" || $toma['estatus']=="limitado"){
            $costo_Agua=0;  
            $costo_alc=0;  
            $costo_san=0;  
            $consumo_agua=$consumo['consumo'];
            $tarifa_num=TarifaServiciosDetalle::where('id_tipo_toma',$toma->id_tipo_toma)->get()->count();
          if ($toma['c_agua']!==null){
           
            if (count($tarifaToma)==$tarifa_num){
                $costo_Agua=$tarifa['agua'];
            }
            else{
                $costo_Agua=$tarifa['agua']*$consumo_agua;
            }
        
          }
          if ($toma['c_alc']!==null){
            $costo_alc=$tarifa['alcantarillado']*$consumo['consumo'];
      
          }
          if ($toma['c_san']!==null){
            $costo_san=$tarifa['saneamiento']*$consumo['consumo'];
    
          }
          $total_facturacion=$costo_Agua+$costo_alc+$costo_san;
          //guardar excepciones en una tabla de proceso
          $facturaInser=[
            "id_periodo"=>$periodo['id'],
            "id_toma"=>$toma['id'],
            "id_consumo"=>$consumo['id'],
            "id_tarifa_servicio"=>$tarifa['id'],
            "monto"=>$total_facturacion,
            "fecha"=>Carbon::parse(helperFechaAhora(),'GMT-7')->format('Y-m-d'),
          ];
          $factura=Factura::create($facturaInser);
          ///Cambiar create  por insert
          $cargoFactura=$this->CargoFactura($factura,$toma,$costo_Agua,$costo_alc, $costo_san,$periodo);
        }
        else{
            $total_facturacion=0;
            $facturaInser=[
                "id_periodo"=>$periodo['id'],
                "id_toma"=>$toma['id'],
                "id_consumo"=>$consumo['id'],
                "id_tarifa_servicio"=>$tarifa['id'],
                "monto"=>$total_facturacion,
                "fecha"=>Carbon::parse(helperFechaAhora(),'GMT-7')->format('Y-m-d'),
              ];
              $factura=Factura::create($facturaInser);
              ///Cambiar create  por insert
              $cargoFactura=null;
        }
       //////

        $convenio=(new ConvenioService())->crearCargoLetraService($toma['id']);

        $descuento=(new DescuentoAsociadoService())->facturarCndescuento($toma['id'], $factura['id']);

        $estatus = (new PagoService())->pagoAutomatico($toma['id'], "toma");
        $recargos=(new FacturaService())->Recargos($toma);
        //////////

      return [$factura,$cargoFactura,$recargos];
    }
    public function CargoFactura($factura,$toma,$costo_agua,$costo_alc,$costo_san,$periodo){
        //$cargos=[];

        $cargos=new Collection();
        $fecha=Carbon::parse(helperFechaAhora(),'GMT-7')->format('Y-m-d');
        $mes=$periodo['nombre'];//Carbon::parse(helperFechaAhora(),'GMT-7')->translatedFormat('F Y'); //QUÉEEEEEEEEEEEEEEEEEEEEEEEE
        if ($costo_agua!=0){
            if ($toma->esDomestica()){
                $costo_Agua_iva=0;
            }
            else{
                $costo_Agua_iva=helperCalcularIVA($costo_agua);
            }
     
            $concepto=ConceptoCatalogo::find(1); //fijo
            $cargoInsert=[
                "id_concepto"=>$concepto['id'],
                "nombre"=>"facturacion ".$concepto['nombre']." ".$mes,
                "id_origen"=>$factura['id'],
                "modelo_origen"=>"facturas",
                "id_dueno"=>$toma['id'],
                "modelo_dueno"=>"toma",
                "monto"=>$costo_agua,
                "iva"=>$costo_Agua_iva,
                "estado"=>"pendiente",
                "fecha_cargo"=>$fecha,
            ];
            $cargo=Cargo::create($cargoInsert);
            $cargos->push($cargo);
        }
        if ($costo_alc!=0){
            $concepto=ConceptoCatalogo::find(2); //fijo
            $costo_alc_iva=helperCalcularIVA($costo_alc);
            $cargoInsert=[
                "id_concepto"=>$concepto['id'],
                "nombre"=>"facturacion ".$concepto['nombre']." ".$mes,
                "id_origen"=>$factura['id'],
                "modelo_origen"=>"facturas",
                "id_dueno"=>$toma['id'],
                "modelo_dueno"=>"toma",
                "monto"=>$costo_alc,
                "iva"=>$costo_alc_iva,
                "estado"=>"pendiente",
                "fecha_cargo"=>$fecha,
            ];
            $cargo=Cargo::create($cargoInsert);
            $cargos->push($cargo);
    
        }
        if ($costo_san!=0){
            $concepto=ConceptoCatalogo::find(3); //fijo
            $costo_san_iva=helperCalcularIVA($costo_san);
            $cargoInsert=[
                "id_concepto"=>$concepto['id'],
                "nombre"=>"facturacion".$concepto['nombre']." ".$mes,
                "id_origen"=>$factura['id'],
                "modelo_origen"=>"facturas",
                "id_dueno"=>$toma['id'],
                "modelo_dueno"=>"toma",
                "monto"=>$costo_san,
                "iva"=>$costo_san_iva,
                "estado"=>"pendiente",
                "fecha_cargo"=>$fecha,
            ];
            $cargo=Cargo::create($cargoInsert);
            $cargos->push($cargo);
        }
        $concepto=ConceptoCatalogo::find(154); //fijo
        $bomberos=1;
        $cargoInsert=[
            "id_concepto"=>$concepto['id'],
            "nombre"=>$concepto['nombre']." ".$mes,
            "id_origen"=>$factura['id'],
            "modelo_origen"=>"facturas",
            "id_dueno"=>$toma['id'],
            "modelo_dueno"=>"toma",
            "monto"=>$bomberos,
            "iva"=>0,
            "estado"=>"pendiente",
            "fecha_cargo"=>$fecha,
        ];
        $cargo=Cargo::create($cargoInsert);
        $cargos->push($cargo);
        return $cargos;
    }

    public function facturaracionPorToma($id_toma){//facturacion toma individual
        $toma=$id_toma;
        $libro= $toma->libro;
        $ruta=$libro->tieneRuta;
        $periodo=$ruta->PeriodoActivo;
        $tarifa=$periodo->tarifa;
        ///facturacion_arreglo
        ///cargos_facturacion_arreglo
        $ExisteFactura=Factura::where('id_periodo',$periodo['id'])->where('id_toma',$toma['id'])->first();
        if ($ExisteFactura){
            $ExistenCargos=Cargo::where('id_origen',$ExisteFactura['id'])->get();
            if (count($ExistenCargos)!=0){
                throw new ErrorException("No se puede facturar una toma con una facturación vigente dentro del mismo periodo");
            }
        }
        
        $consumo=Consumo::where('id_periodo',$periodo->id)->where('id_toma',$toma->id)->where('estado','activo')->first();

        $tarifaToma=Tarifa::servicioToma($tarifa->id,$toma->id_tipo_toma,$consumo->consumo);
                //dispatch(new FacturacionTomaJob($toma));
        $facturaToma=($this->facturar($toma,$tarifaToma,$periodo,$consumo));
        return $facturaToma;
    }
    public function Refacturacion($tomas){ /// pendiente
        //$tomas=Toma::with('libro.tieneRuta.PeriodoActivo.tarifa:id,nombre,estado','libro:id,id_ruta,nombre','libro.tieneRuta:id,nombre')->whereIn('id',$tomas_id)->get();
        $id_toma=$tomas['id'];
        $id_facturacion=$tomas['id_facturacion']; 
        $toma=Toma::find($id_toma);
        $facturaExistente=Factura::find($id_facturacion);

        if ($facturaExistente){
            $tarifa=TarifaServiciosDetalle::find($facturaExistente->id_tarifa_servicio);
            $periodo=Periodo::find($facturaExistente->id_periodo);
            $consumo=Consumo::where('id_periodo',$periodo->id)->where('id_toma',$toma->id)->where('estado','activo')->first();
            (new DescuentoAsociadoService())->cancelarDecuento($toma['id'],$facturaExistente['id']);
            Cargo::where('id_origen',$facturaExistente->id)->where('modelo_origen',"facturas")->update(['estado' => "cancelado"]); 
            $cargosExistentes=Cargo::where('id_origen',$facturaExistente->id)->where('modelo_origen',"facturas")->get();
            
            $factura=$this->facturar($toma, $tarifa,$periodo,$consumo);
            $factura[]=$cargosExistentes;
            return $factura;
        }
        else{
            throw new ErrorException("No existe facturacion para refacturar");
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
    public function Recargos($toma){

        ///Checar facturaciones pasadas a presente
        ///importe recargos id=10
        ///Checar fecha de facturación para recargar con huecos en facturacion
        $facturas=$toma->CargosFacturasVigentes->groupBy('id_origen')->skip(1);
        $cargos=[];
        $concepto_recargos=ConceptoCatalogo::getRecargos();
        $meses_adeudo=1;
        foreach ($facturas as $factura){

            $total = 0;
            foreach ($factura as $monto){
                //$total+=$monto['monto'];
                $total+=$monto->montoPendiente(false);
            }
            $recargos=Cargo::where('id_origen',$factura[0]->id_origen)->where('modelo_origen', $factura[0]->modelo_origen)->where('id_concepto',$concepto_recargos->id)->where('estado','!=','cancelado')->get();
            $meses_recargos=$meses_adeudo-count($recargos); ///meses de adeudo menos meses ya recargados
            //return $meses_recargos;
            for ($i=0;$i<$meses_recargos;$i++){
                $recargo_monto=$total*0.03;
                $cargoInsert=[
                    "id_concepto"=>$concepto_recargos->id,
                    "nombre"=>"Recargo", //agregar facturacion y mes
                    "id_origen"=>$factura[0]->id_origen,
                    "modelo_origen"=>"facturacion",
                    "id_dueno"=>$toma->id,
                    "modelo_dueno"=>"toma",
                    "monto"=>$recargo_monto,
                    "iva"=>0,
                    "estado"=>"pendiente",
                    "fecha_cargo"=>Carbon::parse(helperFechaAhora(),"GMT-7")->format('Y-m-d'),
                ];
                $cargo=Cargo::create($cargoInsert);
                $cargos[]=$cargo;
          
            }
            $meses_adeudo++;
        }
        ///Recorrer facturaciones vigentes

        //Generar el recargo en base a meses adeudados
        return $cargos;
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
        //$factura = Factura::with('consumo.lecturaAnterior','consumo.lecturaActual','periodo','tarifaServicio','toma.cargos','toma.abonos')->where('id_toma',$idToma)->latest()->get();   
        $toma=Toma::with('cargos.abonos')->where('id',$idToma)->get();
        $saldo=$toma[0]->saldoToma();
        return [$toma,$saldo];
    }



}