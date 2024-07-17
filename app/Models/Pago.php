<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
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
}
