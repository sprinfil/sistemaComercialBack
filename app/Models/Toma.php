<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Toma extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "toma";

    protected $fillable = [
        "id_usuario",
        "id_giro_comercial",
        "id_libro",
        "id_codigo_toma",

        "clave_catastral",
        "estatus",
        "calle",
        "entre_calle_1",
        "entre_calle_2",
        "colonia",
        "codigo_postal",
        "localidad",
        "diametro_toma",
        "calle_notificaciones",
        "entre_calle_notificaciones_1",
        "entre_calle_notificaciones_2",
        "tipo_servicio",
        "tipo_toma",
        "tipo_contratacion",
    ];
}
