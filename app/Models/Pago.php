<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "pagos";

    protected $fillable = [
        "total_pagado",
        "forma_pago",
        "fecha_pago",
        "estado",
    ];
    //Pagos con caja
    public function caja () :BelongsTo
    {
        return $this->belongsTo(Caja::class, 'id_pago');
    }

    public function corteCaja() : BelongsTo {
        return $this->belongsTo(corteCaja::class, 'id_pago');
    }

}
