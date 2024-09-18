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
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use MatanYadaev\EloquentSpatial\Objects\Point;

use function PHPUnit\Framework\isNull;

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
        "codigo_toma",
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
        "direccion_notificacion",
        "tipo_servicio",
        "tipo_toma",
        "tipo_contratacion",
        'c_agua',
        'c_alc',
        'c_san',
        'posicion'
    ];

    // Libro
    public function libro(): BelongsTo
    {
        return $this->belongsTo(Libro::class, "id_libro");
    }
    public function ruta(): HasOneThrough
    {
        return $this->hasOneThrough(Ruta::class, Libro::class, 'id', 'id', 'id_libro', 'id_ruta');
    }
    public function calle1(): BelongsTo
    {
        return $this->belongsTo(Calle::class, "calle");
    }
    public function entre_calle2(): BelongsTo
    {
        return $this->belongsTo(Calle::class, "entre_calle_2");
    }
    public function entre_calle1(): BelongsTo
    {
        return $this->belongsTo(Calle::class, "entre_calle_1");
    }
    public function colonia1(): BelongsTo
    {
        return $this->belongsTo(Colonia::class, "colonia");
    }
    // Giro comercial asociado a la toma
    public function giroComercial(): BelongsTo
    {
        return $this->belongsTo(GiroComercialCatalogo::class, 'id_giro_comercial');
    }

    // Tipo de toma asociado a la toma
    public function tipoToma(): BelongsTo
    {
        return $this->belongsTo(TipoToma::class, 'id_tipo_toma');
    }

    // Usuario asociado a la toma
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Contrato asociado a la toma
    public function contrato(): HasMany
    {
        return $this->hasMany(Contrato::class, 'id_toma');
    }
    public function contratoVigente(): HasMany
    {
        return $this->hasMany(Contrato::class, 'id_toma')->where('estatus', '!=', 'cancelado');
    }

    // Medidor asociado a la toma
    public function medidores(): HasMany
    {
        return $this->hasMany(Medidor::class, 'id_toma');
    }

    public function medidorActivo(): HasOne
    {
        return $this->hasOne(Medidor::class, 'id_toma')
            ->where('estatus', 'activo');
    }

    public function desactivarMedidoresActivos()
    {
        // Actualizar todos los medidores activos a inactivo
        return $this->medidorActivo()->update(['estatus' => 'inactivo']);
    }

    //Consumos asociados a la toma
    public function consumo(): HasMany
    {
        return $this->hasMany(Consumo::class, 'id_toma');
    }

    //Toma asociada a una factibilidad
    public function factibilidad(): HasOne
    {
        return $this->hasOne(Factibilidad::class,'id_toma')->latestOfMany();
    }

    public function factibilidades(): HasMany
    {
        return $this->hasMany(Factibilidad::class);
    }

    public function ordenesTrabajo(): HasMany
    {
        return $this->hasMany(OrdenTrabajo::class, 'id_toma');
    }

    public function datos_fiscales(): MorphOne
    {
        return $this->morphOne(DatoFiscal::class, 'origen', 'modelo', 'id_modelo')->latestOfMany();
    }

    public function cargos(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno');
    }

    public function cargosVigentes(): MorphMany
    {
        return $this->MorphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno')->where('estado', 'pendiente')->with('concepto');
    }
    public function cargosVigentesConConcepto(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno')
            ->where('estado', 'pendiente')
            ->with('concepto'); // Cargar la relación 'concepto' junto con los cargos
    }

    public function pagos(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno')->orderBy('id', 'desc');;
    }
    public function pagosPendientes(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno')->where('estado', 'pendiente');
    }
    public function pagosConDetalle(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno')
            ->with(['abonosConCargos']);
    }


    //Consumos asociados a la toma
    public function factura(): HasMany
    {
        return $this->hasMany(Factura::class, 'id_toma');
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

    public function convenios(): MorphMany
    {
        return $this->morphMany(Convenio::class, 'origen', 'modelo_origen', 'id_modelo');
    }

    public function conveniosActivos(): MorphMany
    {
        return $this->morphMany(Convenio::class, 'origen', 'modelo_origen', 'id_modelo')->where('estado','activo');
    }

    public function saldoToma()
    {
        $total_final = 0;
        $cargos_pendientes = $this->cargosVigentes;
        foreach ($cargos_pendientes as $cargo) {
            $total_final += $cargo->montoPendiente();
        }
        return $total_final;
    }

    public function saldoPendiente()
    {
        $total_final = 0;
        $cargos_pendientes = $this->cargosVigentes;
        foreach ($cargos_pendientes as $cargo) {
            $total_final += $cargo->montoPendiente();
        }
        return $total_final;
    }

    public function getSaldo()
    {
        if (!isNull($this->saldo)) {
            return $this->saldo;
        } else {
            return null;
        }
    }
    public function saldoSinAplicar()
    {
        $total_final = 0;
        $pagos_pendientes = $this->pagosPendientes;
        foreach ($pagos_pendientes as $pago) {
            $total_final += $pago->pendiente();
        }
        return $total_final;
    }
    public static function ConsultarUsuarioPorCodigo(string $toma)
    {
        $data = Usuario::whereHas('tomas', function ($query) use ($toma) {
            $query->where('codigo_toma', $toma);
        })->with(['toma' => function ($query) use ($toma) {
            $query->where('codigo_toma', $toma);
        }])
            ->paginate(1);
        return  $data;
    }
}
