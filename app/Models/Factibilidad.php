<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factibilidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'factibilidad';

    protected $fillable = 
    ['estado_factible',
    'monto_derechos_conexion'
    ];
}
