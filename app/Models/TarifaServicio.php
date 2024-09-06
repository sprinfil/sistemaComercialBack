<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TarifaServicio extends Model
{
    use HasFactory;
     protected $fillable = [
        "id_tarifa",
        "id_tipo_toma",
        "genera_iva",
        "tipo_servicio"
    ];

    
    public function tarifa() : BelongsTo
    {
        return $this->belongsTo(Tarifa::class, 'id_tarifa' , 'id');
    }
    
    
    public function tarifaDetalle() : HasMany
    {
        return $this->hasMany(TarifaServiciosDetalle::class, 'id_tarifa_servicio' , 'id');
    }

    public function tipotoma() : BelongsTo {
         return $this->belongsTo(TipoToma::class, 'id_tipo_toma' , 'id');
    }
    
}
