<?php
namespace App\Services;

use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Database\Eloquent\Collection;

class OrdenTrabajoCatalogoService{

    public function store(array $ordenCatalogo){
        $catalogo=OrdenTrabajoCatalogo::where('nombre',$ordenCatalogo['nombre'])->first();
        $concepto=$ordenCatalogo['id_concepto_catalogo'] ?? 0;
        /*
        if ($concepto!=0){
            $catalogo->where('id_concepto_catalogo',$ordenCatalogo['id_concepto_catalogo']);
        }
        else{
            $ordenCatalogo['id_concepto_catalogo']=null;
        }
        $catalogo->first();
        */
        //return $catalogo;
        if ($catalogo){
            return null;
        }
        else{
            $OrdenCatalogo=OrdenTrabajoCatalogo::create($ordenCatalogo);
            return $OrdenCatalogo;
        } 
        /*
        $orden = OrdenTrabajoCatalogo::withTrashed()->where('nombre', $ordenCatalogo['nombre'])->first();
        $Existe=false;
        if ($orden) {
            $Existe=true;
            return [null,$Existe];
        }
        else{
            $OrdenCatalogo=OrdenTrabajoCatalogo::create($ordenCatalogo);
            return [$OrdenCatalogo,$Existe];
        }
            */
    }
    public static function delete($idOrden){
        $ordenTrabajo=OrdenTrabajoCatalogo::findOrFail($idOrden);
        $ordenTrabajo->delete();
    }
}