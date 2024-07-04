<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'nombre_contacto',
        'telefono',
        'curp',
        'rfc',
        'correo',
    ];
}
