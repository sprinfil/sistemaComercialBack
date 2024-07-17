<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajoCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table=[
        "id_orden_trabajo_catalogo",
        "id_concepto_catalogo",
        "accion",
        "momento",
    ];
}
