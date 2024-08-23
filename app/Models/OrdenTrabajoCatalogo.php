<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajoCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='orden_trabajo_catalogos';
    protected $fillable=[
        "nombre",
        "descripcion",
        "vigencias",
        "momento_cargo",
        "genera_masiva",

    ];
    public function ordenTrabajoAccion():HasMany{ 
        return $this->HasMany(OrdenTrabajoAccion::class,'id_orden_trabajo_catalogo');;
    }
    public function ordenTrabajo():HasMany{
        return $this->hasMany(OrdenTrabajo::class,'id_orden_trabajo_catalogo');
    }
    public function ordenTrabajoCargos():HasMany{
        return $this->hasMany(OrdenesTrabajoCargo::class,'id_orden_trabajo_catalogo');
    }
    public function ordenTrabajoEncadenado():HasMany{
        return $this->hasMany(OrdenesTrabajoEncadenada::class,'id_OT_Catalogo_padre');
    }
    public static function BuscarCatalogo($nombre){
        $ordenTrabajo=OrdenTrabajoCatalogo::where('nombre','LIKE','%'.$nombre.'%')->get();
        return $ordenTrabajo;
    }
    
    protected static function boot() //borrado en cascada
    {
        parent::boot();

        static::deleting(function ($parent) {
            // Soft delete related child models
            $parent->ordenTrabajoAccion()->each(function ($child) {
                $child->delete();
            });
            $parent->ordenTrabajoCargos()->each(function ($child) {
                $child->delete();
            });
            $parent->ordenTrabajoEncadenado()->each(function ($child) {
                $child->delete();
            });
        });

        static::restoring(function ($parent) {
            $parent->ordenTrabajoAccion()->withTrashed()->restore();
            $parent->ordenTrabajoCargos()->withTrashed()->restore();
            $parent->ordenTrabajoEncadenado()->withTrashed()->restore();
        });
    }
        
    
}
