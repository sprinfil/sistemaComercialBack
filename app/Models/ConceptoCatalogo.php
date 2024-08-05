<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConceptoCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre",
        "descripcion",
        "estado",
        "prioridad_abono",
        "genera_iva",
        "abonable",
        "tarifa_fija",
        "cargo_directo"
    ];

    // Medidor asociado a la toma
    public function tarifas() : HasMany
    {
        return $this->hasMany(TarifaConceptoDetalle::class, 'id_concepto');
    }

    public function ordenTrabajoCatalogo() : HasMany
    {
        return $this->hasMany(ordenTrabajoCatalogo::class, 'id_concepto_catalogo');
    }

    // Busqueda por nombre
    public static function buscarPorNombre(string $nombre){
        
        $data=ConceptoCatalogo::where('nombre',$nombre)->get()->first();
        return $data;
        

    }
}
