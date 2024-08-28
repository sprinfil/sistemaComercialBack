<?php
namespace App\Services;

use App\Http\Resources\CargoResource;
use App\Http\Resources\OrdenTrabajoResource;
use App\Models\Cargo;
use App\Models\Consumo;
use App\Models\Contrato;
use App\Models\Lectura;
use App\Models\Medidor;
use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoAccion;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use App\Models\Toma;
use App\Models\Usuario;
use App\Services\Caja\ConceptoService;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

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
    
        $ordenTrabajoPeticion['id_empleado_asigno']=$id_empleado_asigno;
      
        $cargo=null;
        if (count($ordenTrabajo)>=$OtCatalogo['limite_ordenes']){
           
            return null;
        }
        else{

            
            
            $ordenTrabajoPeticion['fecha_vigencia']=Carbon::today()->addDays($OtCatalogo['vigencias']);
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
        
        $OT=OrdenTrabajo::find($ordenTrabajo['id']);
        if ($OT['estado']=="En proceso"){
            return null;
        }
        $OT['estado']="En proceso";
        $OT['id_empleado_encargado']=$ordenTrabajo['id_empleado_encargado'];
        $OT->update();
        $OT->save();
        return $OT;
    }

    ///El operador encargado termina la orden de trabajo
    public function concluir(array $ordenTrabajo, $modelos){ //Ejemplo de service
        $OT=OrdenTrabajo::find($ordenTrabajo['id']);
        if ($OT['estado']=="Concluida"){
           return null;
        }
        
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
                $NuevasOt=['id_toma'=>$ordenTrabajo['id_toma'],'id_empleado_asigno'=>$ordenTrabajo['id_empleado_asigno'],'id_orden_trabajo_catalogo'=>$encade['id']];
                $OTencadenadas->push($this->crearOrden($NuevasOt));
            }
        }
        //return "no";

        
        $ordenTrabajo['estado']="Concluida";
        $ordenTrabajo['fecha_finalizada']=Carbon::today()->format('Y-m-d');
        $cargo=null;
    
       
       
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
//validar terminar ot si pagos saldados 
    public function Masiva(array $ordenesTrabajo){
        $catalogo=OrdenTrabajoCatalogo::find($ordenesTrabajo[0]['id_orden_trabajo_catalogo']);
        $Ordenes=new Collection();
        $i=1;
        foreach ($ordenesTrabajo as $OT){
           
            $data=$this->crearOrden($OT);
            if (!$data){
                $Ordenes->push(["Error"=>"La orden de trabajo para la toma ".$OT['id_toma']." no se pudo crear, debido, a que ya supera el limite del tipo de orden de trabajo: ".$catalogo['nombre']]);
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
        $acciones=$OtCatalogo->ordenTrabajoAccion;

        foreach ($acciones as $accion){
            $resultado=match($accion['accion'])
            {
                'modificar'=>$this->Modificar($accion,$ordenTrabajo,$modelos),
                'registrar'=>$this->Registrar($accion,$ordenTrabajo,$modelos),
                'quitar'=>$this->Quitar($accion,$ordenTrabajo,$modelos),
            };
        }
        
        return $resultado;
    }
    //metodos que ejecutan los casos de acciones especificas
    public function Modificar($Accion,$ordenTrabajo,$modelos){
        
        $tipo_modelo=$Accion['modelo'];


        switch($tipo_modelo){
            case "toma":
                $OTModelo=Toma::find($ordenTrabajo['id_toma']);
                $dato=$modelos['toma'];
                $OTModelo->update($dato);
                $OTModelo->save();
                
                break;
            case "medidor":
                $OTModelo=Medidor::where('id_toma',$ordenTrabajo['id_toma'])->first();
                $dato=$modelos['medidor'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            case "contrato":
                $OTModelo=Contrato::where('id_toma',$ordenTrabajo['id_toma'])->first();
                $dato=$modelos['contrato'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            case "usuario":
                $OTModeloHijo=Toma::find($ordenTrabajo['id_toma']);
                $OTModelo=$OTModeloHijo->usuario;
                $dato=$modelos['usuario'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            case "consumo":
                $OTModelo=Consumo::where('id_toma',$ordenTrabajo['id_toma'])->first();
                $dato=$modelos['usuario'];
                $OTModelo->update($dato);
                $OTModelo->save();
                break;
            case "lectura":
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
            case "toma":
                $dato=$modelos['toma'];
                $OTModelo=Toma::create($dato);
                break;
            case "medidor":
                $dato=$modelos['toma'];
                $OTModelo=Medidor::create($dato);
                break;
            case "contrato":
                $dato=$modelos['contrato'];
                $OTModelo=Contrato::create($dato);
                break;
            case "usuario":
                $dato=$modelos['usuario'];
                $OTModelo=Usuario::create($dato);
                break;
            case "consumo":
                $dato=$modelos['usuario'];
                $OTModelo=Consumo::create($dato);
                break;
            case "lectura":
                $dato=$modelos['lectura'];
                $OTModelo=Lectura::create($dato);
                break;
            default:
            $OTModelo=null;
            break;
        }
        return $OTModelo;
    }
    public function Quitar($Accion,$ordenTrabajo,$modelos){
        
    }
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
                'fecha_cargo' => Carbon::today()->format('Y-m-d'),
            ]));
        }
        return $cargos;
    }
    public function cancelar(Request $request){
        $OT=OrdenTrabajo::find($request['id']);
        $OTCatalogo=OrdenTrabajoCatalogo::find($OT['id_orden_trabajo_catalogo']);
        if ($OTCatalogo['momento_cargo']=="generar"){
            $OtCargos=$OT->cargosVigentes;
            foreach ($OtCargos as $cargo){
                $cargo->delete();
            }
        }
        $OT->delete();
    }
    public function restore(){
        
    }
}