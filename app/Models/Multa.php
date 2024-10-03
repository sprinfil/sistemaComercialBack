<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Multa extends Model
{
    use HasFactory , SoftDeletes;

    protected $fillable = [
        'id_multado',
        'id_catalogo_multa',
        'id_operador',
        'id_revisor',
        'modelo_multado',
        'motivo',
        'fecha_solicitud',
        'estado'
    ];

    public function operador_multa() : BelongsTo {
        return $this->belongsTo(Operador::class , 'id_operador' , 'id');
    }

    public function operador_revisor() : BelongsTo {
        return $this->belongsTo(Operador::class , 'id_operador' , 'id');
    }

    public function catalogo_multa() : BelongsTo {
        return $this->belongsTo(MultaCatalogo::class, 'id_catalogo_multa' , 'id');
    }

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_multado', 'id_multado');
    }
}
