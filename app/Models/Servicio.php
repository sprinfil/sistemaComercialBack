<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Servicio extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "servicios";
    protected $fillable = [
        "id_contrato",
        "nombre",
        "id_toma",
    ];

    // Servicio asociado a la toma
    public function toma() : BelongsTo
    {
        return $this->belongsTo(Toma::class, 'id_toma');
    }

    // Servicio asociado al contrato
    public function contrato() : BelongsTo
    {
        return $this->belongsTo(Contrato::class, 'id_contrato');
    }
}