<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_concepto",
        "nombre",
        "id_origen",
        "modelo_origen",
        "id_dueno",
        "modelo_dueno",
        "monto",
        "iva",
        "estado",
        "id_convenio",
        "fecha_cargo",
        "fecha_liquidacion",
    ];

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_origen', 'id_origen');
    }

    public function dueno(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_dueno', 'id_dueno');
    }

    public function abonos(): HasMany
    {
        return $this->hasMany(Abono::class, 'id_cargo');
    }

    public function abonosVigentes(): HasMany
    {
        return $this->hasMany(Abono::class, 'id_cargo')
            ->whereHas('origen', function ($query) {
                $query->where('estado', '!=', 'cancelado');
            });
    }

    public function abonosCancelados(): HasMany
    {
        return $this->hasMany(Abono::class, 'id_cargo')
            ->whereHas('origen', function ($query) {
                $query->where('estado', '==', 'cancelado');
            });
    }

    public function abonosTotal()
    {
        return $this->abonosVigentes()->sum('total_abonado'); // 'monto' es el campo en la tabla 'abonos' que contiene el valor abonado
    }

    public function concepto(): HasOne
    {
        return $this->hasOne(ConceptoCatalogo::class, "id", "id_concepto");
    }

    public function montoOriginal()
    {
        return ($this->monto + $this->iva);
    }

    public function montoPendiente($generaIVA=true) //siempre genera iva al menos que se especifique false en parametro
    {
        if ($generaIVA){
            $monto_pendiente = ($this->monto + $this->iva) - $this->abonosTotal();
            if ($monto_pendiente < 1) {
                $monto_pendiente = 0;
            }
        }
        else{
            $monto_pendiente = ($this->monto) - $this->abonosTotal();
            if ($monto_pendiente < 1) {
                $monto_pendiente = 0;
            }
        }
       
        return $monto_pendiente;
    }

    public function CargoConveniado(): HasOne
    {
        return $this->hasOne(CargosConveniado::class, "id_cargo", "id");
    }
}
