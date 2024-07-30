<?php

namespace App\Models;

use App\Models\Punto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function asignacionGeografica(): MorphOne
    {
        return $this->morphOne(AsignacionGeografica::class, 'asignacionModelo', 'modelo', 'id_modelo');
    }

    
    public function getPuntosAttribute(){
        if($this->asignacionGeografica){
            return Punto::where("id_asignacion_geografica", $this->asignacionGeografica->id)->get();
        }
    }
    
}
