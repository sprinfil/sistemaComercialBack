<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoTomaAplicable extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'id_modelo',
        'modelo_origen',
        'id_tipo_toma'
    ];
}
