<?php

namespace App\Models;

use Hamcrest\Arrays\IsArray;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'id_operador',
        'id_caja_catalogo',
        'fondo_inicial',
        'fondo_final',
        'fecha_apertura',
        'fecha_cierre',
    ];

    //Caja con pagos
    public function pagos() : HasMany
    {
        return $this->hasMany(Pago::class , 'id_caja'); 
    }

    public function pagosPorTipo(string $tipo)
    {
        return $this->pagos()->where('forma_pago', $tipo)->get();
    }

    public function totalPorTipo(string $tipo): float
    {
        $pagos = $this->pagosPorTipo($tipo)->pluck('total_pagado')->toArray();

        // No es necesario volver a verificar si es array ya que pluck()->toArray() siempre devolverá un array.
        if (!empty($pagos)) {
            return $this->sumarTotalPagado($pagos);
        }
        return 0.00;
    }

    public function sumarTotalPagado(array $pagos): float
    {
        // Ya que $pagos es un array de valores, puedes usar array_sum para sumar los totales
        return array_sum($pagos);
    }


    /*public function sumarTotalPagado(array $pagos): float
    {
        // Suma los valores de total_pagado de cada pago en la lista
        $total = array_reduce($pagos, function ($carry, $pago) {
            return $carry + $pago->total_pagado;
        }, 0);

        return $total;
    }*/


    //Corte de caja con cajas
    public function corteCaja() : HasMany 
    {
        return $this->hasMany(CorteCaja::class , 'id_caja'); 
    }

    /* Fondo de una caja
    public function fondoCaja() : HasOne 
    {
        return $this->hasOne(FondoCaja::class, 'id_caja'); 
    } */

    //Operador de caja
    public function operador() : HasOne 
    {
        return $this->hasOne(OperadorAsignado::class, 'id_caja');
    }

    public function catalogoCaja () : BelongsTo
    {
        return $this->belongsTo(CajaCatalogo::class , 'id_caja_catalogo'); //ya
    }
}