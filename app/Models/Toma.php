<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
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
        'c_agua',
        'c_alc_san',
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
        return $this->hasMany(ordenTrabajo::class,'id_toma');;
    }

    public function datos_fiscales(): MorphMany
    {
        return $this->morphMany(DatoFiscal::class, 'origen', 'modelo', 'id_modelo');
    }
    public function TarifaContrato()
    {
        return $this->id;
    }

    //Consulta de referencia (no se usa)
    public static function ConsultarContratosPorToma(string $id_toma){
        
        $data=Toma::findOrFail($id_toma);
        $contratos=$data->withWhereHas('contratoVigente' , function (Builder $query) {
            $query->where('estatus', '!=','cancelado');
            
        })->get();
        return $contratos;
        
    }

    public function getDireccionCompleta()
    {
        return "{$this->calle}, entre {$this->entre_calle_1} y {$this->entre_calle_2}, {$this->colonia}, {$this->codigo_postal}, {$this->localidad}";
    }
}

