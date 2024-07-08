<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DescuentoAsociado extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_usuario",
        "id_toma",
        "id_descuento",
        "folio",
        "evidencia"
    ];
}
