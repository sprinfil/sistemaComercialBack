<?php
namespace App\Services;

use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoCatalogo;
use App\Models\OrdenTrabajoConfiguracion;
use COM;

class OrdenTrabajoService{

    //Momento es cuando se realiza la acción de la OT.
    //Acción es si va a generar un cargo, eliminarlo, o realizar una modificación. 
    //Pueden hacerse modificaciones aún con la acción de generar

    //un operador crea la orden de trabajo y su tipo
    public function crearOrden(array $ordenTrabajo){ //Ejemplo de service
        
        $OrdenCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $OrdenConf=OrdenTrabajoConfiguracion::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $orden=$ordenTrabajo;//eliminar
        if ($OrdenConf['momento']=="generar"){
            $orden=OrdenTrabajo::create($ordenTrabajo);
            $this->generarCargo();
        }
        else{
            $orden=OrdenTrabajo::create($ordenTrabajo);
        }
        
        return $orden;
    }
    public function asignar(array $ordenTrabajo): ?OrdenTrabajo{ //Ejemplo de service
        $OrdenCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $OrdenConf=OrdenTrabajoConfiguracion::find($ordenTrabajo['id_orden_trabajo_catalogo']);
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
        $OrdenConf=OrdenTrabajoConfiguracion::find($ordenTrabajo['id_orden_trabajo_catalogo']);
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