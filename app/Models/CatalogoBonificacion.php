<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CatalogoBonificacion extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "catalogo_bonificaciones";

    protected $fillable = [
        "nombre",
        "descripcion",
        "estado",
    ];
}
