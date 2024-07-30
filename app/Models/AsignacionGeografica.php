<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsignacionGeografica extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asignaciones_geograficas'; 

    protected $fillable = [
        "id_modelo",
        "modelo",
        "estatus"
        
    ];

    public function asignacionModelo(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo', 'id_modelo');
    }

    public function puntos() : HasMany {
        return $this->hasMany(Punto::class , "id_asignacion_geografica");
    }
}
