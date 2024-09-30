<?php
namespace App\Services;

use App\Http\Resources\CargoResource;
use App\Http\Resources\OrdenTrabajoResource;
use App\Models\Cargo;
use App\Models\Consumo;
use App\Models\Contrato;
use App\Models\Lectura;
use App\Models\Libro;
use App\Models\Medidor;
use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoAccion;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use App\Models\Secuencia_orden;
use App\Models\Toma;
use App\Models\Usuario;
use App\Services\Caja\ConceptoService;
use Carbon\Carbon;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Stmt\Else_;
use PhpParser\Node\Stmt\Return_;

use function PHPUnit\Framework\isEmpty;
use function PHPUnit\Framework\isNull;

class OrdenTrabajoService{

    //Momento es cuando se realiza la acción de la OT.
    //Acción es si va a generar un cargo, eliminarlo, o realizar una modificación. 
    //Pueden hacerse modificaciones aún con la acción de generar

    //Agregar el metodo de cargo en los 3 momentos que sufre una OT
    //un operador crea la orden de trabajo y su tipo
    public function crearOrden(array $ordenTrabajoPeticion){ //Ejemplo de service
        $OtCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajoPeticion['id_orden_trabajo_catalogo']);
        $ordenTrabajo=OrdenTrabajo::where('id_toma',$ordenTrabajoPeticion['id_toma'])->where('id_orden_trabajo_catalogo',$ordenTrabajoPeticion['id_orden_trabajo_catalogo'])->whereNot('estado','Concluida')->whereNot('estado','Cancelada')->get();
      
        $id_empleado_asigno=auth()->user()->operador->id;//auth()->user()->operador->id
        $ordenTrabajoPeticion['id_empleado_genero']=$id_empleado_asigno;
      
        $cargo=null;
        if (count($ordenTrabajo)>=$OtCatalogo['limite_ordenes']){
           
            return null;
        }
        else{

            
            
            $ordenTrabajoPeticion['fecha_vigencia']=Carbon::today()->addDays($OtCatalogo['vigencias']);
            $ordenTrabajoPeticion['created_at']= Carbon::now('America/Denver')->format('Y-m-d H:i:s');
            $ordenTrabajoPeticion['estado']="No asignada";
            $ordenTrabajo=OrdenTrabajo::create($ordenTrabajoPeticion);
            if($OtCatalogo['momento_cargo']=="generar"){
                $conceptos=OrdenTrabajoCatalogo::where('id',$OtCatalogo['id'])
                ->with('ordenTrabajoCargos')->first()['ordenTrabajoCargos']
                ->pluck('OTConcepto');
                //LOOOOOOOOOOL  una atalacha que me ahorra un foreach????

                $toma=Toma::find($ordenTrabajoPeticion['id_toma']);
                $origen="orden_trabajo";
                $dueno="toma";
                $cargo=$this->generarCargo($ordenTrabajo,$origen,$toma,$dueno,$conceptos);
            }
            
            return [$ordenTrabajo,$cargo];
            //:?OrdenTrabajo
        } 
    }
    public function asignar(array $ordenTrabajo): ?OrdenTrabajo{ //Ejemplo de service
        $id_empleado_asigno=auth()->user()->operador->id;
  

        $OT=OrdenTrabajo::find($ordenTrabajo['id']);
        if ($OT['estado']=="Concluida" || $OT['estado']=="Cancelada"){
            return null;
        }
        else{
            $OT['fecha_asignacion']=Carbon::today('America/Denver')->format('Y-m-d H:i:s');
            $OT['estado']="En proceso";
            $OT['id_empleado_asigno']=$id_empleado_asigno;
            $OT['id_empleado_encargado']=$ordenTrabajo['id_empleado_encargado'];
            $OT->update();
            $OT->save();
            $OT->empleadoAsigno;
            $OT->empleadoEncargado;
            return $OT;
        }
        
    }

    ///El operador encargado termina la orden de trabajo
    public function concluir(array $ordenTrabajo, $modelos){ //Ejemplo de service
        $OT=OrdenTrabajo::find($ordenTrabajo['id']);
        if ($OT['estado']!="En proceso"){
           return null;
        }
        else{

            $OTencadenadas=new Collection();
            $OrdenCatalogo=OrdenTrabajoCatalogo::find($OT['id_orden_trabajo_catalogo']);
            $OrdenConf=OrdenTrabajoAccion::find($OT['id_orden_trabajo_catalogo']);
    
            $IniciarEncadenadas=$ordenTrabajo['genera_OT_encadenadas'];
       
            //Checa si hay ordenes encadenadas y las ejecuta
            $OrdenesEncadenadas=OrdenTrabajoCatalogo::where('id', $OrdenCatalogo['id'])
            ->with('ordenTrabajoEncadenado.OrdenCatalogoEncadenadas')
            ->first()['ordenTrabajoEncadenado']->pluck('OrdenCatalogoEncadenadas'); //CONSULTA INSANOTA
            if (count($OrdenesEncadenadas)!=0 && $IniciarEncadenadas==true){
                $OTencadenadas=new Collection();
                foreach ($OrdenesEncadenadas as $encade){
                    $NuevasOt=['id_toma'=>$OT['id_toma'],'id_empleado_asigno'=>$OT['id_empleado_asigno'],'id_orden_trabajo_catalogo'=>$encade['id']];
                    $OTencadenadas->push($this->crearOrden($NuevasOt));
                }
            }
            //return "no";
    
            
            $ordenTrabajo['estado']="Concluida";
            $ordenTrabajo['fecha_finalizada']=Carbon::today()->format('Y-m-d');
            $cargo=null;
        
            //$evidencia= $ordenTrabajo['evidencia']->file('image')->store('images', 'public');
            //$ordenTrabajo['evidencia']=$evidencia;
            $OT->update($ordenTrabajo);
            $OT->save($ordenTrabajo);
            $OTAcciones=$this->Acciones($OT, $OrdenCatalogo,$modelos);
            if ($OrdenCatalogo['momento_cargo']=="concluir"){
    
                $conceptos=OrdenTrabajoCatalogo::where('id',$OrdenCatalogo['id'])
                ->with('ordenTrabajoCargos')->first()['ordenTrabajoCargos']
                ->pluck('OTConcepto');
                
                $toma=Toma::find($OT['id_toma']);
                $origen="orden_trabajo";
                $dueno="toma";
                $cargo=$this->generarCargo($OrdenCatalogo,$origen,$toma,$dueno,$conceptos);
                return ["OrdenTrabajo"=>new OrdenTrabajoResource($OT),"Modelo"=>$OTAcciones,"cargos"=>CargoResource::collection($cargo),"OT_encadenadas"=>$OTencadenadas];
            }
            else{
                return ["OrdenTrabajo"=>new OrdenTrabajoResource($OT),"Modelo"=>$OTAcciones,"cargos"=>$cargo,"OT_encadenadas"=>$OTencadenadas];
            }
        }
        
  
    }

//validar terminar ot si pagos saldados 
    public function Masiva(array $ordenesTrabajo){
        $catalogo=OrdenTrabajoCatalogo::find($ordenesTrabajo[0]['id_orden_trabajo_catalogo']);
        $Ordenes=new Collection();
        $i=1;
        foreach ($ordenesTrabajo as $OT){
           
            $data=$this->crearOrden($OT);
            if (!$data){
                $ordenTrabajo=OrdenTrabajo::find($OT['id_toma']);
                $toma=Toma::find($ordenTrabajo['id_toma']);
                $Ordenes->push(["Error"=>"La orden de trabajo para la toma con clave catastral ".$toma['clave_catastral']." no se pudo crear, debido, a que ya supera el limite del tipo de orden de trabajo: ".$catalogo['nombre']]);
            }
            else{
                $Ordenes->push($data);
            }
          $i++;
        }
        return $Ordenes;

    }
    public function AsignarMasiva(array $ordenesTrabajo){
        
        $Ordenes=new Collection();
        $i=1;
        foreach ($ordenesTrabajo as $OT){
            
            $data=$this->asignar($OT);
            if (!$data){
                $ordenTrabajo=OrdenTrabajo::find($OT['id']);
                $toma=Toma::find($ordenTrabajo['id_toma']);
          
                $catalogo=OrdenTrabajoCatalogo::find( $ordenTrabajo['id_orden_trabajo_catalogo']);
                $Ordenes->push(["Error"=>"La orden de trabajo tipo:".$catalogo['nombre'].". Para la toma con clave catastral ".$toma['clave_catastral']." no se pudo asignar, debido a que ya poseia un operador asignado"]);
            }
            else{
                $Ordenes->push($data);
            }
          $i++;
        }
        return $Ordenes;
    }
    public function CancelarMasiva(array $ordenesTrabajo){
        
        $Ordenes=new Collection();
        $i=1;
        foreach ($ordenesTrabajo as $OT){
            
            $data=$this->cancelar($OT);
            if ($data=="error"){
                $ordenTrabajo=OrdenTrabajo::find($OT);
                $toma=Toma::find($ordenTrabajo['id_toma']);
              
                $catalogo=OrdenTrabajoCatalogo::find( $ordenTrabajo['id_orden_trabajo_catalogo']);
                $Ordenes->push(["Error"=>"La orden de trabajo tipo:".$catalogo['nombre'].". Para la toma con clave catastral ".$toma['clave_catastral']." no se pudo cancelar, debido a que ya esta concluida"]);
            }
            else{
              
            }
          $i++;
        }
        return $Ordenes;
    }
    public function CerrarMasiva(array $ordenesTrabajo){
        
        $Ordenes=new Collection();
        $i=1;
        foreach ($ordenesTrabajo as $OT){
            
            $data=$this->concluir($OT, null);
            if (!$data){
                $ordenTrabajo=OrdenTrabajo::find($OT['id']);
                $toma=Toma::find($ordenTrabajo['id_toma']);
           
                $catalogo=OrdenTrabajoCatalogo::find( $ordenTrabajo['id_orden_trabajo_catalogo']);
                $Ordenes->push(["Error"=>"La orden de trabajo tipo:".$catalogo['nombre'].". Para la toma con clave catastral ".$toma['clave_catastral']." no se pudo cerrar, debido a que ya poseia un operador asignado"]);
            }
            else{
                $Ordenes->push($data);
            }
          $i++;
        }
        return $Ordenes;
    }
    //metodo que maneja el tipo de accion de la ot a realizar
    public function Acciones(OrdenTrabajo $ordenTrabajo, $OtCatalogo, $modelos){
        $acciones=$OtCatalogo->ordenTrabajoAccion ?? null;
        if (count($acciones)==0){
            return null;
        }
        else{
            foreach ($acciones as $accion){
                $resultado=match($accion['accion'])
                {
                    'modificar'=>$this->Modificar($accion,$ordenTrabajo,$modelos),
                    'registrar'=>$this->Registrar($accion,$ordenTrabajo,$modelos),
                    //'quitar'=>$this->Quitar($accion,$ordenTrabajo,$modelos),
                };
            }
            return $resultado;
        }
        
        
 
    }
    //metodos que ejecutan los casos de acciones especificas
    public function Modificar($Accion,$ordenTrabajo,$modelos){
        
        $tipo_modelo=$Accion['modelo'];


        switch($tipo_modelo){
            case "toma":
                $OTModelo=Toma::find($ordenTrabajo['id_toma']);
                $servicio=match($Accion['campo']){
                    'contrato_agua'=>"c_agua",
                    'contrato_alcantarillado'=>"c_alc",
                    'contrato_saneamiento'=>"c_san",
                    default=>null
                };
                //Checa caso especifico de instalación o dada de baja de servicios
                if ($Accion['campo']=="contrato_agua" || $Accion['campo']=="contrato_alcantarillado" || $Accion['campo']=="contrato_saneamiento" ){
                    //$servicio=$Accion['campo'];

                  
                    $estado=$Accion['valor'];
                    if ($estado=="activa"){
                        $valor=match($servicio){
                            'c_agua'=>Contrato::where('id_toma',$OTModelo['id'])->where('servicio_contratado','agua')->first()->id,
                            'c_alc'=>Contrato::where('id_toma',$OTModelo['id'])->where('servicio_contratado','alcantarillado y saneamiento')->first()->id,
                            'c_san'=>Contrato::where('id_toma',$OTModelo['id'])->where('servicio_contratado','alcantarillado y saneamiento')->first()->id,
                        };
                        $dato=[$servicio=>$valor];
                        
                        if ($valor=="c_agua"){
                            $libro=$OTModelo->libro;
                            $secuencia=$libro->secuenciasPadre;
                            $orden=[];
                            $orden[]=[
                                "id_secuencia"=>$secuencia->id,
                                "id_toma"=>$OTModelo->id,
                                "numero_secuencia"=>0,
                            ];
                            $Secuencia_orden=Secuencia_orden::insert($orden);
                        }
                        if ($OTModelo['estatus']=="pendiente de instalacion"){
                            $OTModelo['estatus']=="activa";
                        }
                     
                    }
                    else if ($estado=="de baja"){
                        $dato=[$servicio=>null];
                    }
                    

                }
                else{
                    $dato=[$Accion['campo']=>$servicio];
                }
                $OTModelo->update($dato);
                $OTModelo->save();
                
                break;
            case "medidores":
                $OTModelo=Medidor::where('id_toma',$ordenTrabajo['id_toma'])->first();
                $dato=$modelos['medidor'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            case "contratos":
                $OTModelo=Contrato::where('id_toma',$ordenTrabajo['id_toma'])->first();
                $dato=$modelos['contrato'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            case "usuarios":
                $OTModeloHijo=Toma::find($ordenTrabajo['id_toma']);
                $OTModelo=$OTModeloHijo->usuario;
                $dato=$modelos['usuario'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            case "consumos":
                $OTModelo=Consumo::where('id_toma',$ordenTrabajo['id_toma'])->first();
                $dato=$modelos['usuario'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            case "lecturas":
                $OTModelo=Lectura::where('id_toma',$ordenTrabajo['id_toma'])->first();
                $dato=$modelos['lectura'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            default:
            $OTModelo=null;
            break;
        }
        return $OTModelo;
       
    }
   
    public function Registrar($Accion,$ordenTrabajo,$modelos){
        $tipo_modelo=$Accion['modelo'];


        switch($tipo_modelo){
            /*
            case "toma":
                $dato=$modelos['toma'];
                $OTModelo=Toma::create($dato);
                break;
                */
            case "medidores":
                $dato=$modelos['medidores'];
                $dato['estatus']="activo";
                $MedidorExiste=Toma::find($dato['id_toma'])->medidorActivo;
                if ($MedidorExiste){
                    $MedidorExiste->update(["estatus"=>"inactivo"]);
                }
                $dato['id_toma']=$ordenTrabajo['id_toma'];
                $OTModelo=Medidor::create($dato);
                break;
                /*
            case "contratos":
                $dato=$modelos['contratos'];
                $OTModelo=Contrato::create($dato);
                break;
            case "usuarios":
                $dato=$modelos['usuarios'];
                $OTModelo=Usuario::create($dato);
                break;
                
            case "consumos":
                $dato=$modelos['consumos'];
                $OTModelo=Consumo::create($dato);
                break;
            case "lecturas":
                $dato=$modelos['lecturas'];
                $OTModelo=Lectura::create($dato);
                break;
                */
            default:
            $OTModelo=null;
            break;
        }
        return $OTModelo;
    }
    
    public function Quitar($Accion,$ordenTrabajo,$modelos){
        $tipo_modelo=$Accion['modelo'];
        switch($tipo_modelo){
            /*
            case "toma":
                $dato=$modelos['toma'];
                $OTModelo=Toma::delete($dato);
                break;
                */
            case "medidores":
                $OTModelo=Toma::find($ordenTrabajo['id_toma']);
                $medidor=$OTModelo->medidor;
                $medidor->delete();
                //$medidor=Medidor::withTrashed()->where('id_toma',$ordenTrabajo['id_toma'])->first();
                /*
                $dato=$modelos['toma'] ?? null;
                if  ($dato!=null){
                    
                }
                $OTModelo->update($dato);
                */
                break;
                /*
            case "contratos":
                $dato=$modelos['contrato'];
                $OTModelo=Contrato::create($dato);
                break;
            case "usuarios":
                $dato=$modelos['usuario'];
                $OTModelo=Usuario::create($dato);
                break;
            case "consumos":
                $dato=$modelos['consumo'];
                $OTModelo=Consumo::create($dato);
                break;
            case "lecturas":
                $dato=$modelos['lectura'];
                $OTModelo=Lectura::create($dato);
                break;
                */
            default:
            $OTModelo=null;
            break;
        }
        return $OTModelo;
    }
    ////
    public function generarCargo($origen,$tipoOrigen, $dueno,$tipoDueno,$conceptos){

        $cargos=new Collection();
        foreach($conceptos as $concepto){
            $tarifa=(new ConceptoService())->obtenerTarifaToma($dueno['id_tipo_toma'],$concepto['id']);
            $iva=helperCalcularIVA($tarifa['monto']);
            $cargos->push(Cargo::create([
                'id_concepto' => $concepto['id'],
                'nombre' => $concepto['nombre'],
                'id_origen' =>  $origen['id'],
                'modelo_origen' => $tipoOrigen,
                'id_dueno' => $dueno['id'],
                'modelo_dueno' => $tipoDueno,
                'monto' => $tarifa['monto'],
                'iva' => $iva,
                'estado' => "pendiente",
                'fecha_cargo' => helperFechaAhora(),
            ]));
        }
        return $cargos;
    }
    public function cancelar($request){
        $OTCatalogo=OrdenTrabajo::find($request);
        if ($OTCatalogo['estado']=="Concluida"){
        return "error";
        }
        else{
            $OT=OrdenTrabajo::find($request);
           
            if ($OTCatalogo['momento_cargo']=="No genera"){
                $OtCargos=$OT->cargosVigentes;
                foreach ($OtCargos as $cargo){
                    $cargo->delete();
                }
            }
            $OT->delete();
        }
       
    }
    public function restore(){
        
    }
    public function FiltrarOT($filtros){
       
        $ruta=$filtros['ruta_id'] ?? null;
        $libro=$filtros['libro_id'] ?? null;
        $toma=$filtros['toma_id'] ?? null;
        $saldoMin=$filtros['saldo_min'] ?? null;
        $saldoMax=$filtros['saldo_max'] ?? null;
        $Asignada=$filtros['asignada'] ?? false;
        $no_asignada=$filtros['no_asignada'] ?? false;
        $Concluida=$filtros['concluida'] ?? false;
        $Cancelada=$filtros['cancelada'] ?? false;
        $domestica=$filtros['domestica'] ?? null;
        $comercial=$filtros['comercial'] ?? null;
        $industrial=$filtros['industrial'] ?? null;
        $especial=$filtros['especial'] ?? null;
        $sin_contrato=$filtros['sin_contrato'] ?? null;
        $servicio=$filtros['servicio'] ?? null;
        $codigo=$filtros['codigo_toma'] ?? null;
        /////fechas
        $fecha_tipo=$filtros['fecha_tipo'] ?? "created_at";
        $fecha_inicio=$filtros['fecha_inicio'] ?? null;
        $fecha_fin=$filtros['fecha_fin'] ?? null;

         // HIPER MEGA QUERY INSANO
         $query=OrdenTrabajo::with('empleadoGenero','empleadoAsigno','empleadoEncargado','toma.usuario','toma.tipoToma','toma.libro','toma.ruta','ordenTrabajoCatalogo.ordenTrabajoAccion')
         ->when($Asignada, function (EloquentBuilder $q)  {
            return $q->orWhere('estado', 'En proceso');
        })->when($no_asignada, function (EloquentBuilder $q)  {
            return $q->orWhere('estado', 'No asignada');
        })->when($Concluida, function (EloquentBuilder $q)  {
            return $q->orWhere('estado', 'Concluida');
        })->when($Cancelada, function (EloquentBuilder $q)  {
            return $q->orWhere('estado', 'Cancelada');
        })
        ->when($ruta, function (EloquentBuilder $q) use($ruta,$libro)  {

        $q->whereHas('toma', function($a)use($ruta,$libro){
                $a->when($libro, function (EloquentBuilder $a2) use($ruta,$libro){
                    $a2->with('libro')->whereHas('libro', function($b)use($ruta,$libro){
                        $b->where('id',$libro)->with('tieneRuta')->whereHas('tieneRuta', function($c)use($ruta){
                            $c->where('id',$ruta);
                            
                        });
                    });
                },function (EloquentBuilder $a3)use($ruta){
                    $a3->with('libro')->whereHas('libro', function($b)use($ruta){
                        $b->with('tieneRuta')->whereHas('tieneRuta', function($c)use($ruta){
                            $c->where('id',$ruta);
                            
                        });
                    });
                });
                
            });
            return $q;
        })->when($toma, function (EloquentBuilder $q) use($toma,$domestica,$comercial,$industrial,$especial,$sin_contrato) {
            
            $q->whereHas('toma.tipoToma', function($a)use($domestica,$comercial,$industrial,$especial,$sin_contrato){
                $types = [];

                if ($domestica) {
                    $types[] = 'Domestica';
                }
                if ($comercial) {
                    $types[] = 'Comercial';
                }
                if ($industrial) {
                    $types[] = 'Industrial';
                }
                if ($especial) {
                    $types[] = 'Especial';
                }
                if ($sin_contrato) {
                    $types[] = 'Sin Contrato';
                }
        
                if (!empty($types)) {
                    $a->whereIn('nombre', $types);
                }
                
            });
            $q->where('id_toma',$toma);
            
        }
        ,function(EloquentBuilder $q)use($domestica,$comercial,$industrial,$especial,$sin_contrato){
            $q->whereHas('toma.tipoToma', function($a)use($domestica,$comercial,$industrial,$especial,$sin_contrato){
                
                $types = [];

                if ($domestica) {
                    $types[] = 'Domestica';
                }
                if ($comercial) {
                    $types[] = 'Comercial';
                }
                if ($industrial) {
                    $types[] = 'Industrial';
                }
                if ($especial) {
                    $types[] = 'Especial';
                }
                if ($sin_contrato) {
                    $types[] = 'Sin Contrato';
                }
        
                if (!empty($types)) {
                    $a->whereIn('nombre', $types);
                }
            });
        })->when($codigo, function (EloquentBuilder $q) use ($codigo){
            $q->whereHas('toma', function($a)use ($codigo){
                $a->where('codigo_toma',$codigo);
            });
        })->when($fecha_inicio, function (EloquentBuilder $q) use ($fecha_inicio,$fecha_fin,$fecha_tipo){
          
            $q->when($fecha_fin, function (EloquentBuilder $q2) use ($fecha_inicio,$fecha_fin,$fecha_tipo){
                $q2->whereBetween($fecha_tipo,[$fecha_inicio,$fecha_fin]);
            },function(EloquentBuilder $q) use($fecha_inicio,$fecha_tipo){
                $q->where($fecha_tipo,$fecha_inicio);
            });
        })->orderby($fecha_tipo,'desc')
        ->get();

        //TODO CONSULTA SALDO CON Y SIN CONVENIO
        /*
        $query = $query->filter(function($query) {
            $toma=$query->toma;
            if (!empty($toma)){
                $saldo=$toma->saldoToma();
                $toma['saldo']=$saldo;
                unset($toma['cargosVigentes']);
                $resultado=$toma;
                return $resultado;    
                
            }
        });
*/
       
      
        $Querysaldo=new Collection();
        foreach($query as $ot){
            $saldo=$ot['toma']->saldoToma();
            $ot['toma']['saldo']=$saldo;
   
            if ($saldoMin){
                if ($saldoMax){
                    if ($ot['toma']['saldo']>=$saldoMin && $ot['toma']['saldo']<=$saldoMax){
                
                        $Querysaldo->push($ot);
                     
                    }
                }
                
                else{
                    if ($ot['toma']['saldo']==$saldoMin){
                        $Querysaldo->push($ot);
                    }
                }
            
            }
            else{
                $Querysaldo->push($ot);
            }
            unset( $ot['toma']['cargosVigentes']);
           
        }
        
        $OT =$Querysaldo;
        return $OT;
        //
       
       
    }
    public function OtMasivas($tipoOrden){
        match($tipoOrden)
        {
            'generar'=>$ordenesTrabajo=OrdenTrabajoCatalogo::where('genera_masiva',1)->get(),
            'asignar'=>$ordenesTrabajo=OrdenTrabajoCatalogo::where('asigna_masiva',1)->get(),
            'cerrar'=>$ordenesTrabajo=OrdenTrabajoCatalogo::where('cierra_masiva',1)->get(),
            'cancelar'=>$ordenesTrabajo=OrdenTrabajoCatalogo::where('cancela_masiva',1)->get(),
        };
        return $ordenesTrabajo;
    }
}