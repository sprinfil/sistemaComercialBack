<?php
namespace App\Services;

use App\Models\OrdenTrabajoCatalogo;
use Illuminate\Database\Eloquent\Collection;

class OrdenTrabajoCatalogoService{

    public function store(array $ordenCatalogo){
        $catalogo=OrdenTrabajoCatalogo::where('nombre',$ordenCatalogo['nombre'])->first();
        if ($catalogo){
            return null;
        }
        else{
            $OrdenCatalogo=OrdenTrabajoCatalogo::create($ordenCatalogo);
            
            //$this->storeCargos();
            //$this->storeOTEncadenadas();
            return $OrdenCatalogo;
        } 
       
    }
    public static function delete($idOrden){
        $ordenTrabajo=OrdenTrabajoCatalogo::findOrFail($idOrden);
        $ordenTrabajo->delete();
    }
    public static function storeCargos(){

    }
    public static function storeOTEncadenadas(){
        
    }
}