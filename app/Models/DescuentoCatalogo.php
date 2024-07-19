<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class DescuentoCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre",
        "descripcion",
        "estado",
    ];

    // Descuentos asociados a tipo de descuento (monitor)
    public function descuentos() : HasMany
    {
        return $this->hasMany(Toma::class, 'id_descuento_aplicado');
    }

    public function conceptosAplicables(): MorphMany
    {
        return $this->morphMany(ConceptoAplicable::class, 'conceptosAplicables', 'modelo', 'id_modelo');
    }
}
