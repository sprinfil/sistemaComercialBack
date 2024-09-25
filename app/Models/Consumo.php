<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consumo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_toma",
        "id_periodo",
        "id_lectura_anterior",
        "id_lectura_actual",
        "tipo",
        "estado",
        "consumo"
    ];

    //Toma a la que pertence el consumo
    public function toma() : BelongsTo
    {
        return $this->belongsTo(Toma::class, 'id_toma');
    }

    // Consumo que se registra en la lectura
    public function lecturaAnterior() : HasOne
    {
        return $this->hasOne(Lectura::class, 'id_lectura_anterior');
    }

    // Consumo que se registra en la lectura
    public function lecturaActual() : HasOne
    {
        return $this->hasOne(Lectura::class, 'id_lectura_actual');
    }

    public function periodo() : BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'id_periodo');
    }
}
