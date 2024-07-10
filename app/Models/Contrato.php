<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contrato extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'id_toma',
        'id_usuario',
        'folio_solicitud',
        'estatus',
        'nombre_contrato',
        'clave_catastral',
        'tipo_toma',
        'colonia',
        'calle',
        'entre_calle1',
        'entre_calle2',
        'domicilio',
        'diametro_de_la_toma',
        'codigo_postal',
        'coordenada',
    ];
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(Usuario::class, 'id_usuario');
    }

    
}
