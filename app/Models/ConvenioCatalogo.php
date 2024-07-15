<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ConvenioCatalogo extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "nombre",
        "descripcion",
        "estado",
    ];

    public function conceptosAplicables(): MorphMany
    {
        return $this->morphMany(ConceptoAplicable::class, 'conceptosAplicables', 'modelo', 'id_modelo');
    }
}
