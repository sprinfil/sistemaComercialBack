<?php
namespace App\Services;

use App\Http\Resources\OrdenesTrabajoCargoResource;
use App\Http\Resources\OrdenesTrabajoEncadenadaResource;
use App\Http\Resources\OrdenTrabajoCatalogoResource;
use App\Models\OrdenesTrabajoCargo;
use App\Models\OrdenesTrabajoEncadenada;
use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Database\Eloquent\Collection;

class OrdenTrabajoCatalogoService{

    public function store(array $requestCatalogo){
        $ordenCatalogo=$requestCatalogo['orden_trabajo_catalogo'];
        $idCatalogo=$ordenCatalogo['id'] ?? null;

        //create catalogo
        $catalogo=OrdenTrabajoCatalogo::where('nombre',$ordenCatalogo['nombre'])->first();
        if ($catalogo && $idCatalogo==null){
            return "Existe";
        }
        else{
            $OrdenCatalogo=OrdenTrabajoCatalogo::updateOrCreate( ['id' => $idCatalogo],$ordenCatalogo);
            return new OrdenTrabajoCatalogoResource($OrdenCatalogo);
        }
       
    }
   
    public static function delete($idOrden){
        $ordenTrabajo=OrdenTrabajoCatalogo::findOrFail($idOrden);
        $OT=$ordenTrabajo->ordenTrabajo;
        if (count($OT)!=0){
            return "No valido";
        }
        else{
            $ordenTrabajo->delete();
            return "Valido";
        }
 
    }
    public function storeCargos(array $ordenCatalogo){
        $requestCargos=$ordenCatalogo['orden_trabajo_cargos'];
        $OrdenCargos=[];
        $ordenesCargos_id=[];
        //$id=$idcatalogo ?? $requestCargos[0]['id'];
        foreach ($requestCargos as $cargo){
            $idCargo=$cargo['id'] ?? null;
            $OTCatalogo=$cargo['id_orden_trabajo_catalogo'] ?? null;
            $ordenCargo=OrdenesTrabajoCargo::updateOrCreate(['id' =>$idCargo,'id_orden_trabajo_catalogo' =>$OTCatalogo],$cargo);
            $OrdenCargos[]=$ordenCargo;
            $ordenesCargos_id[]=$ordenCargo['id'];
        }
        OrdenesTrabajoCargo::where('id_orden_trabajo_catalogo', $OrdenCargos[0]['id_orden_trabajo_catalogo'])
        ->whereNotIn('id', $ordenesCargos_id)
        ->delete();
        return OrdenesTrabajoCargoResource::collection($OrdenCargos);
       
    }
    public function storeOTEncadenadas(array $ordenCatalogo){
        $requestEncadenadas=$ordenCatalogo['orden_trabajo_encadenadas'];
        $OrdenEncadenadas=[];
        $OrdenEncadenadas_id=[];
        foreach ($requestEncadenadas as $OT){
            $idEncadenada=$OT['id'] ?? null;
            $idOTpadre=$OT['id_OT_Catalogo_padre'] ?? null;
            $ordenEncadenada=OrdenesTrabajoEncadenada::updateOrCreate(['id' =>$idEncadenada,'id_OT_Catalogo_padre' =>$idOTpadre],$OT);
            $OrdenEncadenadas[]=$ordenEncadenada;
            $OrdenEncadenadas_id[]=$ordenEncadenada['id'];
        }
        OrdenesTrabajoEncadenada::where('id_OT_Catalogo_padre', $OrdenEncadenadas[0]['id_OT_Catalogo_padre'])
        ->whereNotIn('id', $OrdenEncadenadas_id)
        ->delete();
        return OrdenesTrabajoEncadenadaResource::collection($OrdenEncadenadas);
    }
}