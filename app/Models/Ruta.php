<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruta extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = "rutas";


    protected $fillable = [
        "nombre"
    ];

    public function Libros() : HasMany {
        return $this->hasMany(Libro::class , "id_ruta");
    }

    public function Periodos() : HasMany {
        return $this->hasMany(Periodo::class , "id_ruta");
    }
    
    //pendiente relaciones de ruta
}
