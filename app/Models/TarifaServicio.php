<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TarifaServicio extends Model
{
    use HasFactory;

    
    public function tarifa() : BelongsTo
    {
        return $this->belongsTo(Tarifa::class, 'id_tarifa');
    }
    
    
    public function tarifaDetalle() : HasMany
    {
        return $this->hasMany(TarifaServiciosDetalle::class, 'id_tarifa_servicio'); //detalles
    }

    public function tipotoma() : BelongsTo {
         return $this->belongsTo(TipoToma::class, 'id_tipo_toma');
    }
    
}

