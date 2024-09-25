<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Periodo extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_ruta",
        "id_tarifa",
        "nombre",
        "periodo",
        "facturacion_fecha_inicio",
        "facturacion_fecha_final",
        "lectura_inicio",
        "lectura_final"
    ];
  
    public function factura():HasMany{
        return $this->HasMany(Factura::class, 'id');
    }

    public function tieneRutas() : BelongsTo {
        return $this->belongsTo(Ruta::class , 'id_ruta');
    }

    public function tarifa() : HasOne {
        return $this->hasOne(Tarifa::class , 'id_tarifa');
    }

    public function cargaTrabajo() : HasMany {
        return $this->hasMany(CargaTrabajo::class , "id_periodo");
    }
}
