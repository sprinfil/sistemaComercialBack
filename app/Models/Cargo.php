<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        "id_origen",
        "modelo_origen",
        "id_dueño",
        "modelo_dueño",
        "monto",
        "estado",
        "fecha_cargo",
        "fecha_liquidacion",
    ];
    
    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_origen', 'id_origen');
    }
}