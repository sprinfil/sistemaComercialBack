<?php
namespace App\Services\Facturacion;

use App\Http\Resources\FacturaResource;
use App\Jobs\FacturacionTomaJob;
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

                    //dispatch(new FacturacionTomaJob($toma));
                    if ($consumo){
                        $tarifaToma=Tarifa::servicioToma($tarifa->id,$toma->id_tipo_toma,$consumo->consumo);
                       
                  
                        $facturaToma=($this->facturar($toma,$tarifaToma,$periodo,$consumo));
                       
                        $periodosFactura->push($facturaToma[0]);
                        $facturaCargos->push($facturaToma[1]);
                        
                    }
                   
                }
            }
        }
        return [$periodosFactura, $facturaCargos];             
    }

    public function facturar($toma,$tarifaToma,$periodo,$consumo){
      
        if ($toma['estatus']=="activa" || $toma['estatus']=="limitado"){
            $costo_Agua=0;  
            $costo_alc=0;  
            $costo_san=0;  
            $consumo_agua=$consumo['consumo'];
            if  ($consumo_agua<17){
                $consumo_agua=17;
            }
          if ($toma['c_agua']!==null){
            $costo_Agua=$tarifaToma['agua']*$consumo_agua;
        
          }
          if ($toma['c_alc']!==null){
            $costo_alc=$tarifaToma['alcantarillado']*$consumo['consumo'];
      
          }
          if ($toma['c_san']!==null){
            $costo_san=$tarifaToma['saneamiento']*$consumo['consumo'];
    
          }
          $total_facturacion=$costo_Agua+$costo_alc+$costo_san;
          //guardar excepciones en una tabla de proceso
          $facturaInser=[
            "id_periodo"=>$periodo['id'],
            "id_toma"=>$toma['id'],
            "id_consumo"=>$consumo['id'],
            "id_tarifa_servicio"=>$tarifaToma['id'],
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
                "id_tarifa_servicio"=>$tarifaToma['id'],
                "monto"=>$total_facturacion,
                "fecha"=>Carbon::parse(helperFechaAhora(),'GMT-7')->format('Y-m-d'),
              ];
              $factura=Factura::create($facturaInser);
              ///Cambiar create  por insert
              $cargoFactura=null;
        }
       

      return [$factura,$cargoFactura];
    }
    public function CargoFactura($factura,$toma,$costo_agua,$costo_alc,$costo_san,$periodo){
        //$cargos=[];

        $cargos=new Collection();
        $fecha=Carbon::parse(helperFechaAhora(),'GMT-7')->format('Y-m-d');
        $mes=$periodo['nombre'];//Carbon::parse(helperFechaAhora(),'GMT-7')->translatedFormat('F Y'); //QUÉEEEEEEEEEEEEEEEEEEEEEEEE
        if ($costo_agua!=0){
            $costo_Agua_iva=helperCalcularIVA($costo_agua);
            $concepto=ConceptoCatalogo::find(1); //fijo
            $cargoInsert=[
                "id_concepto"=>$concepto['id'],
                "nombre"=>"facturacion servicio agua ".$mes,
                "id_origen"=>$factura['id'],
                "modelo_origen"=>"facturacion",
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
                "nombre"=>"facturacion servicio alcantarillado ".$mes,
                "id_origen"=>$factura['id'],
                "modelo_origen"=>"facturacion",
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
                "nombre"=>"facturacion servicio saneamiento ".$mes,
                "id_origen"=>$factura['id'],
                "modelo_origen"=>"facturacion",
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
        //dispatch(new FacturacionTomaJob($toma));
        $tarifaToma=Tarifa::servicioToma($tarifa->id,$toma->id_tipo_toma,$consumo->consumo);
        $facturaToma=($this->facturar($toma,$tarifaToma,$periodo,$consumo));

       
            //metodo para aplicar descuentos

            //Aplicar convenios

        return $facturaToma;
    }
    public function Refacturacion($idToma){ /// pendiente
        $toma=Toma::find($idToma);
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
        //dispatch(new FacturacionTomaJob($toma));
        $tarifaToma=Tarifa::servicioToma($tarifa->id,$toma->id_tipo_toma,$consumo->consumo);
        $facturaToma=($this->facturar($toma,$tarifaToma,$periodo,$consumo));
        
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
        $facturas=$toma->CargosFacturasVigentes->groupBy('id_origen');
        $meses_adeudo=count($facturas)-1;
        $totales=[];
        $concepto_recargos=ConceptoCatalogo::getRecargos();
        foreach ($facturas as $factura){
            $total = 0;
            foreach ($factura as $monto){
                //$total+=$monto['monto'];
                $total+=$monto->montoPendiente(false);
            }
        
            $recargos=Cargo::where('id_origen',$factura[0]->id_origen)->where('modelo_origen', $factura[0]->modelo_origen)->where('id_concepto',$concepto_recargos->id)->where('estado','!=','cancelado')->where('estado','!=','conveniado')->get();
            $meses_recargos=$meses_adeudo-count($recargos); ///meses de adeudo menos meses ya recargados
            for ($i=0;$i<$meses_adeudo;$i++){
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
                return $cargo;
            }
     
   
            //$totales[]=$total;
           
        }
        ///Recorrer facturaciones vigentes

        //Generar el recargo en base a meses adeudados
        return $facturas;
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
        $factura = Factura::with('consumo.lecturaAnterior','consumo.lecturaActual','periodo','tarifaServicio')->where('id_toma',$idToma)->latest()->get();         
        return $factura;
    }



}