<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoTomaAplicable extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_modelo',
        'modelo_origen',
        'id_tipo_toma'
    ];

    public function origen(): MorphTo 
    {
        return $this->morphTo(__FUNCTION__, 'modelo_origen', 'id_modelo');
    }

    public function tipoToma(): BelongsTo
    {
        return $this->belongsTo(TipoToma::class, 'id_tipo_toma');
    }
}
