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
        return $this->hasMany(Abono::class,'id_cargo');
    }

    public function abonosTotal()
    {
        return $this->abonos()->sum('total_abonado'); // 'monto' es el campo en la tabla 'abonos' que contiene el valor abonado
    }

    public function concepto(): HasOne{
        return $this->hasOne(ConceptoCatalogo::class, "id", "id_concepto");
    }

    public function montoPendiente()
    {
        return ($this->monto + $this->iva) - $this->abonosTotal();
    }
}