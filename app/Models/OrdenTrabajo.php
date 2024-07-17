<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrdenTrabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $table=[
        "id_toma",
        "id_empleado_asigno",
        "id_empleado_encargado",
        "estado",
        "fecha_finalizada",
        "obervaciones",
        "id_orden_trabajo_catalogo",
        "material_utilizado",
        "evidencia",
    ];
}
