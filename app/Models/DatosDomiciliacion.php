<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatosDomiciliacion extends Model
{
    use HasFactory;
    use HasFactory, SoftDeletes;
    protected $table = "datos_domiciliados";

    protected $fillable = [
        "id_toma",
        "numero_cuenta",
        "fecha_vencimiento",
        "tipo_tarjeta",
        "limite_cobro",
        "domicilio_tarjeta",
    ];
}