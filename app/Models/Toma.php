<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use MatanYadaev\EloquentSpatial\Objects\Point;

class Toma extends Model
{
    use HasFactory, SoftDeletes;
    use HasSpatial;

    protected $table = "toma";
  
    protected $casts = [
        'posicion' => Point::class,
    ];
    protected $spatialFields = [
        'posicion',
    ];
 
    protected $fillable = [
        "id_usuario",
        "id_giro_comercial",
        "id_libro",
        "id_codigo_toma",
        "id_tipo_toma",
        "clave_catastral",
        "estatus",
        "calle",
        "entre_calle_1",
        "entre_calle_2",
        "colonia",
        "codigo_postal",
        "numero_casa",
        "localidad",
        "diametro_toma",
        "calle_notificaciones",
        "entre_calle_notificaciones_1",
        "entre_calle_notificaciones_2",
        "tipo_servicio",
        "tipo_toma",
        "tipo_contratacion",
        'c_agua',
        'c_alc',
        'c_san',
        'posicion'
    ];
    
    // Giro comercial asociado a la toma
    public function giroComercial() : BelongsTo
    {
        return $this->belongsTo(GiroComercialCatalogo::class, 'id_giro_comercial');
    }

    // Tipo de toma asociado a la toma
    public function tipoToma() : BelongsTo
    {
        return $this->belongsTo(TipoToma::class, 'id_tipo_toma');
    }

    // Usuario asociado a la toma
    public function usuario() : BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Contrato asociado a la toma
    public function contrato() : HasMany
    {
        return $this->hasMany(Contrato::class, 'id_toma');
    }
    public function contratoVigente() : HasMany
    {
        return $this->hasMany(Contrato::class, 'id_toma')->where('estatus','!=','cancelado');
    }

     // Medidor asociado a la toma
    public function medidor() : HasOne
    {
        return $this->hasOne(Medidor::class, 'id_toma');
    }

    //Consumos asociados a la toma
    public function consumo():HasMany{
        return $this->hasMany(Consumo::class,'id_toma');
    }

    //Toma asociada a una factibilidad
    public function factibilidad () : HasOne
    {
        return $this->hasOne(Factibilidad::class);
    }

    public function ordenesTrabajo():HasMany{
        return $this->hasMany(OrdenTrabajo::class,'id_toma');
    }

    public function datos_fiscales(): MorphOne
    {
        return $this->MorphOne(DatoFiscal::class, 'origen', 'modelo', 'id_modelo');
    }

    public function cargos(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno');
    }

    public function cargosVigentes(): MorphMany
    {
        return $this->MorphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno')->where('estado','pendiente');
    }
    public function pagos(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno')->orderBy('fecha_pago', 'desc');;
    }
    public function pagosPendientes(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno')->where('estado','pendiente');
    }

    //Consumos asociados a la toma
    public function factura():HasMany{
        return $this->hasMany(Factura::class,'id_toma');
    }
    public function TarifaContrato()
    {
        return $this->id;
    }

    public function asignacionGeografica(): MorphOne
    {
        return $this->morphOne(AsignacionGeografica::class, 'asignacionModelo', 'modelo', 'id_modelo');
    }

    public function getDireccionCompleta()
    {
        return "{$this->calle}, entre {$this->entre_calle_1} y {$this->entre_calle_2}, {$this->colonia}, {$this->codigo_postal}, {$this->localidad}";
    }
}

