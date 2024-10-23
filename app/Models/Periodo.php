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
        "validacion_inicio",
        "validacion_final",
        "lectura_inicio",
        "lectura_final",
        "recibo_inicio",
        "recibo_final",
        "vigencia_recibo",
        "estatus"
    ];

    public function factura():HasMany{
        return $this->HasMany(Factura::class, 'id');
    }

    public function tieneRutas() : BelongsTo {
        return $this->belongsTo(Ruta::class , 'id_ruta');
    }

    public function tarifa() : BelongsTo {
        return $this->belongsTo(Tarifa::class , 'id_tarifa');
    }

    public function cargaTrabajo() : HasMany {
        return $this->hasMany(CargaTrabajo::class , "id_periodo");
    }
    public function cargaTrabajoVigente() : HasMany {
        return $this->hasMany(CargaTrabajo::class , "id_periodo")->whereNot('estado','concluida')->whereNot('estado','cancelada');
    }
    public function consumos():HasMany{
        return $this->hasMany(Consumo::class , "id_periodo");
    }

    public function letras() : HasMany {
        return $this->hasMany(Letra::class , "periodo","nombre");
    }
}
