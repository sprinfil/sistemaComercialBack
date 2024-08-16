<?php
namespace App\Services;

use App\Http\Requests\StoreOrdenTrabajoConfRequest;
use App\Http\Resources\OrdenTrabajoAccionResource;
use App\Http\Resources\OrdenTrabajoConfResource;
use App\Models\OrdenTrabajoAccion;

class OrdenTrabajoAccionService{

    public function store(array $ordenCatalogo, $idcatalogo){ //Ejemplo de service
        $ordenAcciones=$ordenCatalogo['orden_trabajo_accion'] ?? null;
        if  ($ordenAcciones==null){
            return  null;
        }
        else{
            //$id=$idcatalogo ?? $ordenAcciones['id_orden_trabajo_catalogo'];
            return  $ordenCatalogo['id_orden_trabajo_catalogo'];
            $OrdenAcciones=[];
            $OrdenAcciones_id=[];
            foreach ($ordenAcciones as $accion){
                $accion['id_orden_trabajo_catalogo']=$id;
                $idAccion=$accion['id'] ?? null;
                $ordenAccion=OrdenTrabajoAccion::updateOrCreate(['id' =>$idAccion],$accion);
                $OrdenAcciones_id[]=$ordenAccion['id'];
                $OrdenAcciones[]=$ordenAccion;
                
            }
            OrdenTrabajoAccion::where('id_orden_trabajo_catalogo', $OrdenAcciones[0]['id_orden_trabajo_catalogo'])
        ->whereNotIn('id', $OrdenAcciones_id)
        ->delete();
            return OrdenTrabajoAccionResource::collection($OrdenAcciones);
        }
        
    }

}