<?php
namespace App\Services;

use App\Models\OrdenTrabajo;
use App\Models\OrdenTrabajoCatalogo;
use COM;

class OrdenTrabajoService{

    public function store(array $ordenTrabajo): ?OrdenTrabajo{ //Ejemplo de service
        
        $OrdenCatalogo=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $OrdenConf=OrdenTrabajoCatalogo::find($ordenTrabajo['id_orden_trabajo_catalogo']);
        $OrdenTrabajo=null;
        return $OrdenTrabajo;
    }
}