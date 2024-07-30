<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "pagos";

    protected $fillable = [
        "id_caja",
        "id_dueño",
        "modelo_dueño",
        "id_corte_caja",
        "total_pagado",
        "forma_pago",
        "fecha_pago",
        "estado",
    ];
    //Pagos con caja
    public function caja() : BelongsTo {
        return $this->belongsTo(Caja::class , 'id_caja'); 
    }

    //pagos con corte de caja
    public function corteCaja () : HasMany
    {
        return $this->hasMany(corteCaja::class, 'id_pago'); 
    }

    public function dueño(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_dueño', 'id_dueño');
    }
}
