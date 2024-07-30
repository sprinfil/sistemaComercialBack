<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifaConceptoDetalle extends Model
{
    use HasFactory, SoftDeletes;
    
    
    protected $fillable = [
        "id_tipo_toma",
        "id_concepto",
        "monto"
    ];

    // Tipo de toma asociado al concepto detalle de tarifa
    public function tipoToma() : BelongsTo
    {
        return $this->belongsTo(TipoToma::class, 'id_tipo_toma');
    }

    // Concepto asociado al concepto detalle de tarifa
    public function concepto() : BelongsTo
    {
        return $this->belongsTo(ConceptoCatalogo::class, 'id_concepto');
    }

    public function getNombreConcepto(){
        
    }
}
