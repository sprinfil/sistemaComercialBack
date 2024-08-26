<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CajaCatalogo extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'id_cuenta_contable',
        'nombre_caja',
        'hora_apertura',
        'hora_cierre',
    ];

    //Relacion del catalogo de caja con sus diferentes registros en caja
    public function caja () : HasMany
    {
        return $this->hasMany(Caja::class , 'id_caja_catalogo');//ya
    }
    //Relacion del catalogo de cajas con operador arignado
    public function operadorAsignado () : HasMany
    {
        return $this->hasMany(OperadorAsignado::class , 'id_caja_catalogo');
    }
}
