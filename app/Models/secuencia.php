<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Secuencia extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "secuencias";
    protected $fillable = [
        "id_empleado",
        "id_libro",
        "tipo_secuencia"
    ];
    public function ordenesSecuencia():HasMany{
        return $this->hasMany(Secuencia_orden::class,'id_secuencia');
    }
    public function empleado():BelongsTo{
        return $this->belongsTo(Operador::class,'id_empleado');
    }
    public function libro():BelongsTo{
        return $this->belongsTo(Libro::class,'id_libro');
    }
}
