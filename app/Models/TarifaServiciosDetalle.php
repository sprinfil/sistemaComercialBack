<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class TarifaServiciosDetalle extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "id_tarifa_servicio",
        "rango",
        "monto",
    ];
    
    
    public function tarifa() : BelongsTo
     {
         return $this->belongsTo(Tarifa::class, 'id_tarifa_servicio');
     }

     // Tipo de toma asociado al concepto detalle de tarifa
     public function toma() : BelongsTo
     {
         return $this->belongsTo(TipoToma::class, 'id_tipo_toma');
     }

     //facturas asociadas a la tarifaServicio
     public function factura() : HasMany
     {
         return $this->HasMany(Factura::class, 'id');
     }
     
     public function tarifaServicio() : BelongsTo {
        return $this->belongsTo(TarifaServicio::class, 'id_tarifa_servicio');
     }
    
}
