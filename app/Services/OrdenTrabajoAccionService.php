<?php
namespace App\Services;

use App\Http\Requests\StoreOrdenTrabajoConfRequest;
use App\Http\Resources\OrdenTrabajoConfResource;
use App\Models\OrdenTrabajoAccion;

class OrdenTrabajoAccionService{

    public function store(array $ordenCatalogo): ?OrdenTrabajoAccion{ //Ejemplo de service
       /*
        $catalogo=OrdenTrabajoAccion::where('id_orden_trabajo_catalogo',$ordenCatalogo['id_orden_trabajo_catalogo'])->
        where('accion',$ordenCatalogo['accion'])->
        where('modelo',$ordenCatalogo['modelo'])->first();
        //where('id_orden_trabajo_conf_encadenada',$ordenCatalogo['id_orden_trabajo_conf_encadenada'])->
        //where('id_orden_trabajo_conf_alterna',$ordenCatalogo['id_orden_trabajo_conf_alterna'])->first();
        */
        $respuesta=OrdenTrabajoAccion::create($ordenCatalogo);
        return $respuesta;
    }
    /*
    public function show(array $ordenCatalogo): ?OrdenTrabajoAccion{ //Ejemplo de service
       
    
    }
    */
}