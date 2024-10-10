<?php

namespace App\Models;

use App\Models\Punto;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use Illuminate\Database\Eloquent\Relations\HasOne;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Libro extends Model
{
    use HasFactory, SoftDeletes;
    use HasSpatial;
    protected $fillable = [
        "id_ruta",
        "nombre",
        "latitud",
        "longitud"
        
    ];

    protected $spatialFields = [
        'polygon', 
    ];
    
    protected $casts = [
        'polygon' => Polygon::class,
    ];
 
    public function tieneRuta() : BelongsTo {
        return $this->belongsTo(Ruta::class , "id_ruta");
    }
    public function cargaTrabajo() : HasMany {
        return $this->hasMany(CargaTrabajo::class , "id_libro");
    }

    public function asignacionGeografica(): MorphOne
    {
        return $this->morphOne(AsignacionGeografica::class, 'asignacionModelo', 'modelo', 'id_modelo');
    }

    public function tomas() : HasMany {
        return $this->hasMany(Toma::class , "id_libro");
    }
  
    public function secuencias() : HasMany {
        return $this->hasMany(Secuencia::class , "id_libro");
    }
    public function secuenciasPadre() : HasOne {
        return $this->hasOne(Secuencia::class , "id_libro")->where('tipo_secuencia',"padre");
    }
    public function countTomas(): int
    {
        return $this->tomas()->count();
    }
    
    public function getPuntosAttribute(){
        if($this->asignacionGeografica){
            return Punto::where("id_asignacion_geografica", $this->asignacionGeografica->id)->get();
        }
    }
    
}
