<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruta extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "rutas";


    protected $fillable = [
        "nombre",
        "color"
    ];

    public function Libros() : HasMany {
        return $this->hasMany(Libro::class , "id_ruta");
    }

    public function Periodos() : HasMany {
        return $this->hasMany(Periodo::class , "id_ruta");
    }

    public function asignacionGeografica(): MorphOne
    {
        return $this->morphOne(AsignacionGeografica::class, 'asignacionModelo', 'modelo', 'id_modelo');
    }
    
    
}
