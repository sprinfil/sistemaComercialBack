<?php
namespace App\Services;

use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoAccion;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use Carbon\Carbon;
use Illuminate\Http\Request;

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
    public function asignar(array $ordenTrabajo): ?OrdenTrabajo{ //Ejemplo de service
        
        $ordenTrabajoPeticion['estatus']="En proceso";
        return null;
    }

    ///El operador encargado termina la orden de trabajo
    public function concluir(Request $ordenTrabajo){ //Ejemplo de service
        $OrdenCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $OrdenConf=OrdenTrabajoAccion::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        //$ordenTrabajoPeticion['estatus']="Concluida";
        if ($OrdenConf['momento']=="concluir"){

            //generar cargo
        }
        else{

        }
        return null;
    }
    public function Acciones(Request $ordenTrabajo){
        $OT=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $acciones=$OT->ordenTrabajoAccion;
        foreach ($acciones as $accion){
            $resultado=match($accion['accion'])
            {
                'modificar'=>$this->Modificar($accion),
                'registrar'=>$this->Registrar(),
                'quitar'=>$this->Quitar(),
            };
        }
        
        return $acciones;
    }
    public function Modificar($Accion){
        
    }
    public function Registrar(){
        
    }
    public function Quitar(){
        
    }
    public function generarCargo(){

    }
    public function delete(){

    }
    public function restore(){
        
    }
}