<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargosConveniable extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'id_concepto_catalogo',
        'id_convenio_catalogo'
    ];

    public function ConvenioCatalogo():BelongsTo{
        return $this->belongsTo(ConvenioCatalogo::class, "id_convenio_catalogo", "id");
    }

    public function ConceptoCatalogo():BelongsTo{
        return $this->belongsTo(ConceptoCatalogo::class, "id_concepto_catalogo", "id");
    }
}
