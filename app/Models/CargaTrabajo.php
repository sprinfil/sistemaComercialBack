<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CargaTrabajo extends Model
{
    use HasFactory;

    
    public function tieneEncargado() : BelongsTo {
        return $this->belongsTo(Operador::class, "id_operador_encargado");
    }
    public function fueAsignada() : BelongsTo {
        return $this->belongsTo(Operador::class, "id_operador_asigno");
    }
    public function libro() : BelongsTo {
        return $this->belongsTo(libro::class, "id_libro");
    }
    public function periodo() : BelongsTo {
        return $this->belongsTo(periodo::class , "id_periodo");
    }
    
}
