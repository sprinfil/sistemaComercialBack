<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CargaTrabajo extends Model
{
    use HasFactory;

    
    public function tieneEncargado() : BelongsToMany {
        return $this->belongsToMany(Operador::class, "id_operador");
    }
    public function fueAsignada() : BelongsToMany {
        return $this->belongsToMany(Operador::class, "id_operador");
    }
}
