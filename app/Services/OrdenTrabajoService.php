<?php
namespace App\Services;

use App\Http\Resources\OrdenTrabajoResource;
use App\Models\Consumo;
use App\Models\Contrato;
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
        
        $ordenTrabajo=OrdenTrabajo::where('id_toma',$ordenTrabajoPeticion['id_toma'])->where('id_orden_trabajo_catalogo',$ordenTrabajoPeticion['id_orden_trabajo_catalogo'])->whereNot('estado','Concluida')->orWhereNot('estado','Cancelada')->first();
        if ($ordenTrabajo){
            return null;
        }
        else{
            $OtCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajoPeticion['id_orden_trabajo_catalogo']);
          
            $ordenTrabajoPeticion['fecha_vigencia']=Carbon::today()->addDays($OtCatalogo['vigencias']);
            $ordenTrabajoPeticion['estado']="No asignada";
            $OrdenCatalogo=OrdenTrabajo::create($ordenTrabajoPeticion);
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
        $OrdenCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $OrdenConf=OrdenTrabajoAccion::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $ordenTrabajo['estado']="Concluida";
        $ordenTrabajo['fecha_finalizada']=Carbon::today()->format('Y-m-d');
        $OT=OrdenTrabajo::find($ordenTrabajo['id']);

        if ($OT['estado']=="Concluida"){
            return null;
        }
        $OT->update($ordenTrabajo);
        $OT->save($ordenTrabajo);

        if ($OrdenConf['momento']=="concluir"){

            //generar cargo
        }

        $OTAcciones=$this->Acciones($ordenTrabajo, $OrdenCatalogo,$modelos);
        return ["OrdenTrabajo"=>new OrdenTrabajoResource($OT),"Modelo"=>$OTAcciones];
        //return ["OrdenTrabajo"=>new OrdenTrabajoResource($OT)];
    }
    //metodo que maneja el tipo de accion de la ot a realizar
    public function Acciones(array $ordenTrabajo, $OtCatalogo, $modelos){
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
                break;
            case "contrato":
                $OTModelo=Contrato::where('id_toma',$ordenTrabajo['id_toma'])->first();
                break;
            case "usuario":
                $OTModelo=Toma::where('id_toma',$ordenTrabajo['id_toma'])->first();
                break;
            case "consumo":
                $OTModelo=Consumo::where('id_toma',$ordenTrabajo['id_toma'])->first();
                break;
            default:
            $OTModelo=null;
            break;
        }
        return $OTModelo;
       
    }
   
    public function Registrar($Accion,$ordenTrabajo,$modelos){
        
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