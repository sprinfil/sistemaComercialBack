<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Constancia extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "id_catalogo_constancia",
        "estado",
        "id_operador",
        "id_dueno",
        "modelo_dueno",
        "vigencia",
    ];

    public function constanciaCatalogo () : BelongsTo
    {
        return $this->belongsTo(ConstanciaCatalogo::class , 'id_catalogo_constancia'); 
    }

    public function operador () : BelongsTo
    {
        return $this->belongsTo(Operador::class , 'id_operador'); 
    }

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_dueno', 'id_dueno'); 
    }

    public function archivo(): MorphOne
    {
        return $this->morphOne(Archivo::class, 'origen', 'modelo', 'id_modelo'); 
    }

    
}
