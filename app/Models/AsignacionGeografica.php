<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AsignacionGeografica extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'asignaciones_geograficas'; 

    protected $fillable = [
        "id_modelo",
        "modelo",
        "latitud",
        "longitud",
        "estatus"
        
    ];
}
