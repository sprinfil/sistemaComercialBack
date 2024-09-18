<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class AjusteCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre",
        "descripcion",
        "estado",
        "facturable"
    ];

    public function conceptosAplicables(): MorphMany
    {
        return $this->morphMany(ConceptoAplicable::class, 'conceptosAplicables', 'modelo', 'id_modelo');
    }

    public function tipoTomaAplicables(): MorphMany
    {
        return $this->morphMany(TipoTomaAplicable::class, "tipoTomasAplicables", "modelo", "id_modelo");
    }
}
