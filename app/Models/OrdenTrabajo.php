<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajo extends Model
{
    use HasFactory, SoftDeletes;
    //protected $table="orden_trabajos";
    protected $fillable=[
        "id_toma",
        "id_empleado_asigno",
        "id_empleado_encargado",
        "id_orden_trabajo_catalogo",
        "estado",
        "fecha_finalizada",
        "fecha_vigencia",
        "obervaciones",
        "material_utilizado",
        "evidencia",
        "posicion_OT",
    ];

    public function toma():BelongsTo{
        return $this->belongsTo(Toma::class,'id_toma');;
    }
    public function empleadoAsigno():BelongsTo{
        return $this->belongsTo(Operador::class,'id_empleado_asigno');;
    }
    public function empleadoEncargado():BelongsTo{
        return $this->belongsTo(Operador::class,'id_empleado_encargado');;
    }
    public function ordenTrabajoCatalogo():BelongsTo{
        return $this->belongsTo(OrdenTrabajoCatalogo::class,'id_orden_trabajo_catalogo');
    }
    public function cargos(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'origen', 'modelo_origen', 'id_origen');
    }
   
    
}
