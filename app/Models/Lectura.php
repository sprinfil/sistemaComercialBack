<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lectura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_empleado_lecturista",
        "id_toma",
        "id_carga_trabajo",
        "lectura"
        
    ];
     // Consumo que se registra en la lectura
     public function consumo() : HasOne
     {
         return $this->hasOne(Consumo::class, 'id_toma');
     }
}
