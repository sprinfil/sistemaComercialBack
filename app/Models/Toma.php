<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Toma extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "toma";

    protected $fillable = [
        "id_usuario",
        "id_giro_comercial",
        "id_libro",
        "id_codigo_toma",
        "clave_catastral",
        "estatus",
        "calle",
        "entre_calle_1",
        "entre_calle_2",
        "colonia",
        "codigo_postal",
        "localidad",
        "diametro_toma",
        "calle_notificaciones",
        "entre_calle_notificaciones_1",
        "entre_calle_notificaciones_2",
        "tipo_servicio",
        "tipo_toma",
        "tipo_contratacion",
    ];

    
    // Giro comercial asociado a la toma
    public function giroComercial() : BelongsTo
    {
        return $this->belongsTo(GiroComercialCatalogo::class, 'id_giro_comercial');
    }

    // Tipo de toma asociado a la toma
    public function tipoToma() : BelongsTo
    {
        return $this->belongsTo(TipoToma::class, 'tipo_toma', 'nombre');
    }

    // Usuario asociado a la toma
    public function usuario() : BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Servicio asociado a la toma
    public function servicio() : HasMany
    {
        return $this->hasMany(Servicio::class, 'id_toma');
    }

    // Contrato asociado a la toma
    public function contrato() : HasMany
    {
        return $this->hasMany(Contrato::class, 'id_toma');
    }

     // Medidor asociado a la toma
    public function medidor() : HasOne
    {
        return $this->hasOne(Medidor::class, 'id_toma');
    }
}
