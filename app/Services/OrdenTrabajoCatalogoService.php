<?php
namespace App\Services;

use App\Models\OrdenesTrabajoCargo;
use App\Models\OrdenesTrabajoEncadenada;
use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Database\Eloquent\Collection;

class OrdenTrabajoCatalogoService{

    public function store(array $requestCatalogo){
        $ordenCatalogo=$requestCatalogo['orden_trabajo_catalogo'];
        $dataConf=$requestCatalogo['orden_trabajo_accion'] ?? null;
        $dataCargos=$requestCatalogo['orden_trabajo_cargos'] ?? null;
        $dataEncadenadas=$requestCatalogo['orden_trabajo_encadenadas'] ?? null;

        //create catalogo
        $catalogo=OrdenTrabajoCatalogo::where('nombre',$ordenCatalogo['nombre'])->first();
        if ($catalogo){
            return null;
        }
        else{
            $OrdenCatalogo=OrdenTrabajoCatalogo::create($ordenCatalogo);
            if  ($dataConf){
                $OrdenCatalogo['orden_trabajo_acciones']=(new OrdenTrabajoAccionService())->store($dataConf,$OrdenCatalogo['id']);
               
            }
            if  ($dataCargos){
               
                $OrdenCatalogo['ordenes_trabajo_cargos']=$this->storeCargos($dataCargos,$OrdenCatalogo['id']);
                
               
            }
            if  ($dataEncadenadas){
                $OrdenCatalogo['ordenes_trabajo_encadenadas']=$this->storeOTEncadenadas($dataEncadenadas,$OrdenCatalogo['id']);
               
            }
            return $OrdenCatalogo;
        }
    }
   
    public static function delete($idOrden){
        $ordenTrabajo=OrdenTrabajoCatalogo::findOrFail($idOrden);
        $ordenTrabajo->delete();
    }
    public static function storeCargos(array $ordenCatalogo, $idcatalogo){
        $OrdenCargos=[];
        foreach ($ordenCatalogo as $cargo){
        $cargo['id_orden_trabajo_catalogo']=$idcatalogo;
        $OrdenCargos[]=OrdenesTrabajoCargo::create($cargo);
        }

        return $OrdenCargos;
    }
    public function storeOTEncadenadas(array $ordenCatalogo, $idcatalogo){
        $OrdenCargos=[];
        foreach ($ordenCatalogo as $OT){
        $OT['id_OT_Catalogo_padre']=$idcatalogo;
        $OrdenCargos[]=OrdenesTrabajoEncadenada::create($OT);
        }

        return $OrdenCargos;
    }
}