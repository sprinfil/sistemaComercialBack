<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_ruta",
        "id_tarifa",
        "facturacion_fecha_inicio",
        "facturacion_fecha_final",
        "lectura_inicio",
        "lectura_final"
    ];
  
    //Consumos asociados a la toma
    public function factura():HasMany{
        return $this->HasMany(factura::class, 'id');
    }

    public function tieneRutas() : BelongsTo {
        return $this->belongsTo(Ruta::class , 'id_ruta');
    }
    public function cargaTrabajo() : HasMany {
        return $this->hasMany(cargaTrabajo::class , "id_periodo");
    }
}
