<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Descuento extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "descuentos";

    protected $fillable = [
        "id_descuento_aplicado",
        "id_descuento_asociado",
        "monto_total",
        "estado",
    ];

    // Tipo asociado al descuento
    public function tipo() : BelongsTo
    {
        return $this->belongsTo(DescuentoCatalogo::class, 'id_descuento_aplicado', 'id');
    }

    // Origen del descuento
    public function origen() : BelongsTo
    {
        return $this->belongsTo(DescuentoAsociado::class, 'id_descuento_asociado', 'id');
    }
}