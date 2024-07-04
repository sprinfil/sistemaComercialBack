<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Dato_fiscal extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "dato_fiscales";

    protected $fillable = [
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
        "codigo_postal",
        "tipo_modelo"
    ];
}
