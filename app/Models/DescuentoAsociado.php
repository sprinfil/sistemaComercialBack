<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class DescuentoAsociado extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_descuento",
        "id_modelo",
        "modelo_dueno",
        "id_evidencia",
        "id_registra",
        "vigencia",
        "estatus",
        "folio"
    ];

    // Usuario asociado al descuento
    public function usuario() : BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    // Toma asociada al descuento
    public function toma() : BelongsTo
    {
        return $this->belongsTo(Toma::class, 'id_toma');
    }

    // Origen del descuento
    public function descuento() : BelongsTo
    {
        return $this->belongsTo(Descuento::class, 'id_descuento');
    }
}
