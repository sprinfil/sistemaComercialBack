<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lectura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_operador",
        "id_toma",
        "id_periodo",
        "id_origen",
        "modelo_origen",
        "id_anomalia",
        "lectura",
        "comentario"
        
    ];

    public function toma() : BelongsTo
    {
        return $this->belongsTo(Toma::class, 'id_toma');
    }

    public function operador() : HasOne
    {
        return $this->hasOne(Operador::class, 'id', 'id_operador');
    }

    public function periodo() : BelongsTo
    {
        return $this->belongsTo(Periodo::class, 'id_periodo');
    }

    public function anomalia() : HasOne
    {
        return $this->hasOne(AnomaliaCatalogo::class, 'id','id_anomalia');
    }

    public function origen(): MorphOne
    {
        return $this->morphOne(__FUNCTION__, 'modelo_origen', 'id_origen');
    }
}
