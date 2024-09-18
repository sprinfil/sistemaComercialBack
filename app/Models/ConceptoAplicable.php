<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ConceptoAplicable extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "concepto_aplicables";

    protected $fillable = [
        "id_concepto_catalogo",
        "id_modelo",
        "modelo",
        "tipo_bonificacion",
        "porcentaje_bonificable",
        "monto_bonificable"
    ];

    public function conceptosAplicables(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo', 'id_modelo');
    }

    public function concepto(): BelongsTo
    {
        return $this->belongsTo(ConceptoCatalogo::class, 'id_concepto_catalogo');
    }
}
