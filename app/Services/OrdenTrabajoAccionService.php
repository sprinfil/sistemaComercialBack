<?php
namespace App\Services;

use App\Http\Requests\StoreOrdenTrabajoConfRequest;
use App\Http\Resources\OrdenTrabajoConfResource;
use App\Models\OrdenTrabajoAccion;

class OrdenTrabajoAccionService{

    public function store(array $ordenCatalogo, $idcatalogo){ //Ejemplo de service
       
        $OrdenAcciones=[];
        foreach ($ordenCatalogo as $accion){
            $accion['id_orden_trabajo_catalogo']=$idcatalogo;
            $OrdenAcciones[]=OrdenTrabajoAccion::create($accion);
        }
        return $OrdenAcciones;
    }

}