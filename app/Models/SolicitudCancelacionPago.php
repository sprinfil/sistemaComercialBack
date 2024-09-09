<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudCancelacionPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "solicitud_cancelacion_pagos";

    protected $fillable = [
        "id_solicitante",
        "id_caja",
        "folio",
        "estado",
        "id_revisor"
    ];

    // Relación con Pago
    public function pago(): BelongsTo
    {
        return $this->belongsTo(Pago::class, 'folio', 'folio');
    }

    // Relación con Pago
    public function caja(): BelongsTo
    {
        return $this->belongsTo(Caja::class, 'id_caja');
    }

    public function solicitante(): HasOne
    {
        return $this->hasOne(Operador::class, 'id', 'id_solicitante');
    }

    public function revisor(): HasOne
    {
        return $this->hasOne(Operador::class, 'id', 'id_revisor');
    }
}
