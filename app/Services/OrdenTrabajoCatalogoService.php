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
        $ordenCatalogo=$requestCatalogo['orden_trabajo_catalogo'] ?? null;
        if  ($ordenCatalogo==null){
            return null;
        }
        else{
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
       
    }
   
    public static function delete($idOrden){
        $ordenTrabajo=OrdenTrabajoCatalogo::findOrFail($idOrden);
        $ordenTrabajo->delete();
    }
    public static function storeCargos(array $ordenCatalogo, $idcatalogo){
        $requestCargos=$ordenCatalogo['orden_trabajo_cargos'] ?? null;
        if ($requestCargos==null){
            return null;
        }
        else{
            $OrdenCargos=[];
            $ordenesCargos_id=[];
            $id=$idcatalogo ?? $requestCargos;
            foreach ($requestCargos as $cargo){
                $cargo['id_orden_trabajo_catalogo']=$id;
                $idCargo=$cargo['id'] ?? null;
                $ordenCargo=OrdenesTrabajoCargo::updateOrCreate(['id' =>$idCargo],$cargo);
                $OrdenCargos[]=$ordenCargo;
                $ordenesCargos_id[]=$ordenCargo['id'];
            }
            OrdenesTrabajoCargo::where('id_orden_trabajo_catalogo', $OrdenCargos[0]['id_orden_trabajo_catalogo'])
            ->whereNotIn('id', $ordenesCargos_id)
            ->delete();
            return OrdenesTrabajoCargoResource::collection($OrdenCargos);
        }
       
    }
    public function storeOTEncadenadas(array $ordenCatalogo, $idcatalogo){
        $requestEncadenadas=$ordenCatalogo['orden_trabajo_encadenadas'] ?? null;
        if ($requestEncadenadas==null){
            return null;
        }
        else{
            $OrdenEncadenadas=[];
            $OrdenEncadenadas_id=[];
            $id=$idcatalogo ?? $requestEncadenadas;
            foreach ($requestEncadenadas as $OT){
            $idEncadenada=$OT['id'] ?? null;
            $OT['id_OT_Catalogo_padre']=$id;
            $ordenEncadenada=OrdenesTrabajoEncadenada::updateOrCreate(['id' =>$idEncadenada],$OT);
            $OrdenEncadenadas[]=$ordenEncadenada;
            $ordenesEncadenadas_id[]=$ordenEncadenada['id'];
            }
            OrdenesTrabajoEncadenada::where('id_OT_Catalogo_padre', $OrdenEncadenadas[0]['id_OT_Catalogo_padre'])
            ->whereNotIn('id', $ordenesEncadenadas_id)
            ->delete();
            return OrdenesTrabajoEncadenadaResource::collection($OrdenEncadenadas);
        }
        
        
    }
}