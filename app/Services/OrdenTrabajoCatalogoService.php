<?php
namespace App\Services;

use App\Models\OrdenesTrabajoCargo;
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
                $dataConf['id_orden_trabajo_catalogo']=$OrdenCatalogo['id'];
                $OrdenCatalogo['orden_trabajo_accion']=(new OrdenTrabajoAccionService())->store($dataConf);
               
            }
            if  ($dataCargos){
                $dataCargos['id_orden_trabajo_catalogo']=$OrdenCatalogo['id'];
                $OrdenCatalogo['orden_trabajo_accion']=$this->storeCargos($dataCargos);
               
            }
            if  ($dataEncadenadas){
                $dataEncadenadas['id_OT_catalogo_padre']=$OrdenCatalogo['id'];
                $OrdenCatalogo['orden_trabajo_accion']=$this->storeEncadenadas($dataEncadenadas);
               
            }
            //return $OrdenCatalogo;
        }
    }
   
    public static function delete($idOrden){
        $ordenTrabajo=OrdenTrabajoCatalogo::findOrFail($idOrden);
        $ordenTrabajo->delete();
    }
    public static function storeCargos(array $ordenCatalogo){
        $OrdenCargos=[];
        foreach ($ordenCatalogo as $concepto){
        $OrdenCargos[]=OrdenesTrabajoCargo::create($concepto);
        }

        return $OrdenCargos;
    }
    public function storeEncadenadas(array $ordenCatalogo){

    }
    public static function storeOTEncadenadas(){
        
    }
}