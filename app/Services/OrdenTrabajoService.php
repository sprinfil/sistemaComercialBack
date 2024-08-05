<?php
namespace App\Services;

use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoAccion;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use Carbon\Carbon;
use COM;

class OrdenTrabajoService{

    //Momento es cuando se realiza la acción de la OT.
    //Acción es si va a generar un cargo, eliminarlo, o realizar una modificación. 
    //Pueden hacerse modificaciones aún con la acción de generar

    //un operador crea la orden de trabajo y su tipo
    public function crearOrden(array $ordenTrabajoPeticion):?OrdenTrabajo{ //Ejemplo de service
        
        $ordenTrabajo=OrdenTrabajo::where('id_toma',$ordenTrabajoPeticion['id_toma'])->where('id_orden_trabajo_catalogo',$ordenTrabajoPeticion['id_orden_trabajo_catalogo'])->whereNot('estado','Concluida')->orWhereNot('estado','Cancelada')->first();
        if ($ordenTrabajo){
            return null;
        }
        else{
            $OtCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajoPeticion['id_orden_trabajo_catalogo']);
            $OrdenTrabajo['fecha_vigencia']=Carbon::today()->addDays($OtCatalogo['vigencia']);
            $OrdenCatalogo=OrdenTrabajo::create($ordenTrabajo);
            return $OrdenCatalogo;
        } 
    }
    public function EjecutarAcciones(){

    }
    public function asignar(array $ordenTrabajo): ?OrdenTrabajo{ //Ejemplo de service
        $OrdenCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $OrdenConf=OrdenTrabajoAccion::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        if ($OrdenConf['momento']=="asignar"){

            //generar cargo
        }
        else{

        }
        return null;
    }

    ///El operador encargado termina la orden de trabajo
    public function concluir(array $ordenTrabajo): ?OrdenTrabajo{ //Ejemplo de service
        $OrdenCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $OrdenConf=OrdenTrabajoAccion::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        if ($OrdenConf['momento']=="concluir"){

            //generar cargo
        }
        else{

        }
        return null;
    }
    public function generarCargo(){

    }
    public function delete(){

    }
    public function restore(){
        
    }
}