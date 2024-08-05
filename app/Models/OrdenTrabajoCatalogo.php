<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajoCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='orden_trabajo_catalogos';
    protected $fillable=[
        "id_concepto_catalogo",
        "nombre",
        "vigencias",
        "momento_cargo",
        "genera_masiva",

    ];
    public function concepto():BelongsTo{
        return $this->belongsTo(ConceptoCatalogo::class,'id_concepto_catalogo');
    }
    public function ordenTrabajoAccion():HasMany{
        return $this->HasMany(OrdenTrabajoAccion::class,'id_orden_trabajo_catalogo');;
    }
    public function ordenTrabajo():HasMany{
        return $this->hasMany(OrdenTrabajo::class,'id_orden_trabajo_catalogo');
    }
    public static function BuscarCatalogo($nombre){
        $ordenTrabajo=OrdenTrabajoCatalogo::where('nombre','LIKE','%'.$nombre.'%')->get();
        return $ordenTrabajo;
    }
    /*
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($parent) {
            // Soft delete related child models
            $parent->OrdenTrabajoConfiguracion()->each(function ($child) {
                $child->delete();
            });
        });

        static::restoring(function ($parent) {
            $parent->OrdenTrabajoConfiguracion()->withTrashed()->restore();
        });
    }
        */
    
}
