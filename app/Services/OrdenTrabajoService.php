<?php
namespace App\Services;

use App\Http\Resources\OrdenTrabajoResource;
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
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class OrdenTrabajoService{

    //Momento es cuando se realiza la acción de la OT.
    //Acción es si va a generar un cargo, eliminarlo, o realizar una modificación. 
    //Pueden hacerse modificaciones aún con la acción de generar

    //Agregar el metodo de cargo en los 3 momentos que sufre una OT
    //un operador crea la orden de trabajo y su tipo
    public function crearOrden(array $ordenTrabajoPeticion){ //Ejemplo de service
        
        $ordenTrabajo=OrdenTrabajo::where('id_toma',$ordenTrabajoPeticion['id_toma'])->where('id_orden_trabajo_catalogo',$ordenTrabajoPeticion['id_orden_trabajo_catalogo'])->whereNot('estado','Concluida')->whereNot('estado','Cancelada')->first();
        if ($ordenTrabajo){
            return null;
        }
        else{
            $OtCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajoPeticion['id_orden_trabajo_catalogo']);
          
            $ordenTrabajoPeticion['fecha_vigencia']=Carbon::today()->addDays($OtCatalogo['vigencias']);
            $ordenTrabajoPeticion['estado']="No asignada";
            $OrdenCatalogo=OrdenTrabajo::create($ordenTrabajoPeticion);
            if($OtCatalogo['momento_cargo']=="generar"){
                $cargo=$this->generarCargo();
            }
            else{

            }
            return $OrdenCatalogo;
            //:?OrdenTrabajo
        } 
    }
    public function asignar(array $ordenTrabajo): OrdenTrabajo{ //Ejemplo de service
        
        $OT=OrdenTrabajo::find($ordenTrabajo['id']);
        $OT['estado']="En proceso";
        $OT['id_empleado_encargado']=$ordenTrabajo['id_empleado_encargado'];
        $OT->update();
        $OT->save();
        return $OT;
    }

    ///El operador encargado termina la orden de trabajo
    public function concluir(array $ordenTrabajo, $modelos){ //Ejemplo de service
        $OT=OrdenTrabajo::find($ordenTrabajo['id']);
       
        $OrdenCatalogo=OrdenTrabajoCatalogo::find($OT['id_orden_trabajo_catalogo']);
        $OrdenConf=OrdenTrabajoAccion::find($OT['id_orden_trabajo_catalogo']);
        $ordenTrabajo['estado']="Concluida";
        $ordenTrabajo['fecha_finalizada']=Carbon::today()->format('Y-m-d');
     

        if ($OT['estado']=="Concluida"){
            return null;
        }
       
        $OT->update($ordenTrabajo);
        $OT->save($ordenTrabajo);
     
        if ($OrdenCatalogo['momento_cargo']=="concluir"){

            //$cargo=$this->generarCargo();
        }
        //return $modelos;
        $OTAcciones=$this->Acciones($OT, $OrdenCatalogo,$modelos);
        return ["OrdenTrabajo"=>new OrdenTrabajoResource($OT),"Modelo"=>$OTAcciones];
        //return ["OrdenTrabajo"=>new OrdenTrabajoResource($OT)];
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
    public function generarCargo(){

    }
    public function delete(){

    }
    public function restore(){
        
    }
}