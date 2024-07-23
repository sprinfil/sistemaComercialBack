<?php
namespace App\Services;

use App\Models\OrdenTrabajoCatalogo;

class OrdenTrabajoCatalogoService{

    public function store(array $ordenCatalogo): OrdenTrabajoCatalogo{
        $OrdenCatalogo=OrdenTrabajoCatalogo::create($ordenCatalogo);
        return $OrdenCatalogo;
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