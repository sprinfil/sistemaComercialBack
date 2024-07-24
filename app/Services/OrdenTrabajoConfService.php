<?php
namespace App\Services;

use App\Http\Requests\StoreOrdenTrabajoConfRequest;
use App\Http\Resources\OrdenTrabajoConfResource;
use App\Models\OrdenTrabajoConfiguracion;

class OrdenTrabajoConfService{

    public function store(array $ordenCatalogo): ?OrdenTrabajoConfiguracion{ //Ejemplo de service
       
        $catalogo=OrdenTrabajoConfiguracion::where('id_orden_trabajo_catalogo',$ordenCatalogo['id_orden_trabajo_catalogo'])->
        where('id_concepto_catalogo',$ordenCatalogo['id_concepto_catalogo'])->
        where('accion',$ordenCatalogo['accion'])->
        where('momento',$ordenCatalogo['momento'])->
        where('atributo',$ordenCatalogo['atributo'])->
        where('valor',$ordenCatalogo['valor'])->first();
        if ($catalogo){
            return null;
        }
        else{
            $respuesta=OrdenTrabajoConfiguracion::create($ordenCatalogo);
            return $respuesta;
        }  
    }
    public function show(array $ordenCatalogo): ?OrdenTrabajoConfiguracion{ //Ejemplo de service
       
    
    }
}