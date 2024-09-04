<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Medidor extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "medidores";
    protected $fillable = [
        "id_toma",
        "numero_serie",
        "marca",
        "diametro",
        "tipo",
        "estatus",
        "fecha_instalacion",
        "lectura_inicial"
    ];

     // Toma asociada al medidor
    public function toma() : BelongsTo
    {
        return $this->belongsTo(Toma::class, 'id_toma');
    }
}
