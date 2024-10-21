<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CargaTrabajo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        "id_libro",
        "id_operador_encargado",
        "id_periodo",
        "id_operador_asigno",
        "estado",
        "fecha_concluida",
        "fecha_asignacion",
        "tipo_carga"

    ];
    public function tieneEncargado() : BelongsTo {
        return $this->belongsTo(Operador::class, "id_operador_encargado");
    }
    public function fueAsignada() : BelongsTo {
        return $this->belongsTo(Operador::class, "id_operador_asigno");
    }
    public function libro() : BelongsTo {
        return $this->belongsTo(Libro::class, "id_libro");
    }
    public function periodo() : BelongsTo {
        return $this->belongsTo(Periodo::class , "id_periodo");
    }
    
}
