<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajoCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='orden_trabajo_catalogos';
    protected $fillable=[
        "nombre",
    ];
    public function ordenTrabajoConfiguracion():HasOne{
        return $this->hasOne(OrdenTrabajoConfiguracion::class,'id_orden_trabajo_catalogo');;
    }
    public function ordenTrabajo():HasMany{
        return $this->hasMany(ordenTrabajo::class,'id_orden_trabajo_catalogo');
    }
    
}
