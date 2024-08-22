<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorreccionSolicitud extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "correccion_solicitudes";

    protected $fillable = [
        //aqui me quede solo modifique migraciones y esta cosa
        "id_tipo",
        "id_empleado_solicita",
        "id_empleado_registra",
        "tipo_correccion",
        "fecha_solicitud",
        "fecha_correccion",
        "comentario"
    ];
}
