<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use MatanYadaev\EloquentSpatial\Objects\Polygon;
use MatanYadaev\EloquentSpatial\Traits\HasSpatial;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AsignacionGeografica extends Model
{
    use HasFactory, SoftDeletes;
    use HasSpatial;

    protected $table = 'asignaciones_geograficas'; 

    protected $fillable = [
        "id_modelo",
        "modelo",
        "estatus",
        "polygon"
    ];

      protected $casts = [
        'polygon' => Polygon::class,
    ];

   

    public function asignacionModelo(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo', 'id_modelo');
    }

    public function puntos() : HasMany {
        return $this->hasMany(Punto::class , "id_asignacion_geografica");
    }
}
