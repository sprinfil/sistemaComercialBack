<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DescuentoAsociado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_usuario",
        "id_toma",
        "id_descuento",
        "folio",
        "evidencia"
    ];
}
