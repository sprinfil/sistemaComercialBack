<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifaServiciosDetalle extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "id_tarifa",
        "id_tipo_toma",
        "rango",
        "agua",
        "alcantarillado",
        "saneamiento"
    ];
    
    
    public function tarifa() : BelongsTo
     {
         return $this->belongsTo(tarifa::class, 'id_tarifa');
     }

     // Tipo de toma asociado al concepto detalle de tarifa
     public function toma() : BelongsTo
     {
         return $this->belongsTo(TipoToma::class, 'id_tipo_toma');
     }

    
}
