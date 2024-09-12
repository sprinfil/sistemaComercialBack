<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Stmt\Return_;

class Factibilidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'factibilidad';

    protected $fillable =
    [
        'id_toma',
        'id_solicitante',
        'id_revisor',
        'estado',
        'agua_estado_factible',
        'alc_estado_factible',
        //'san_estado_factible',
        'derechos_conexion',
        //'documento',
        'comentario'
    ];

    public function toma(): ?BelongsTo
    {
        try {
            return $this->belongsTo(Toma::class, 'id_toma');
        } catch (Exception $ex) {
            return null;
        }
    }

    public function solicitante(): HasOne
    {
        return $this->hasOne(Operador::class, 'id', 'id_solicitante');
    }

    public function revisor(): HasOne
    {
        return $this->hasOne(Operador::class, 'id', 'id_revisor');
    }

    public function archivos(): MorphMany
    {
        return $this->morphMany(Archivo::class, 'origen', 'modelo', 'id_modelo');
    }
}
