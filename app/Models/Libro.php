<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Libro extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_ruta",
        "nombre",
        "latitud",
        "longitud"
        
    ];
 
    public function tieneRuta() : BelongsTo {
        return $this->belongsTo(Ruta::class , "id_ruta");
    }
    public function cargaTrabajo() : HasMany {
        return $this->hasMany(cargaTrabajo::class , "id_libro");
    }

    //pendiente relaciones libro
}
