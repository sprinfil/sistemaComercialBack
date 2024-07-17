<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConceptoCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre",
        "descripcion",
        "estado",
        "prioridad_abono",
    ];

    // Medidor asociado a la toma
    public function tarifa() : HasOne
    {
        return $this->hasOne(TarifaConceptoDetalle::class, 'id');
    }
}
