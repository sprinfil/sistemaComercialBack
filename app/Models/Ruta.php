<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ruta extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "nombre"
    ];

    //pendiente relaciones de ruta
}