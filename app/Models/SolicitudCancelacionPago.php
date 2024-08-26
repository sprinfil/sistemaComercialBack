<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudCancelacionPago extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "solicitud_cancelacion_pagos";

    protected $fillable = [
        "id_operador",
        "id_caja",
        "folio",
    ];
}