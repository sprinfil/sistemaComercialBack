<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DatoFiscal extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "datos_fiscales";

    protected $fillable = [
        "id_modelo",
        "modelo",
        "regimen_fiscal",
        "correo",
        "razon_social",
        "telefono",
        "pais",
        "estado",
        "municipio",
        "localidad",
        "colonia",
        "calle",
        "referencia",
        "numero_exterior",
        "codigo_postal"
    ];

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo', 'id_modelo');
    }
}
