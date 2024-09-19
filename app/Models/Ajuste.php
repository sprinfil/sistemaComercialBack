<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ajuste extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ajustes'; // Nombre de la tabla en la base de datos

    protected $fillable = [
        'id_ajuste_catalogo',
        'id_modelo_dueno',
        'modelo_dueno',
        'id_operador',
        'monto_ajustado',
        'monto_total',
        'estado',
        'comentario',
        'motivo_cancelacion',
    ];

    /**
     * Relación con el modelo AjusteCatalogo.
     */
    public function ajusteCatalogo()
    {
        return $this->belongsTo(AjusteCatalogo::class, 'id_ajuste_catalogo');
    }

    /**
     * Relación con el modelo dueño (puede ser Toma o Usuario).
     */
    public function dueno()
    {
        if ($this->modelo_dueno == 'toma') {
            return $this->belongsTo(Toma::class, 'id_modelo_dueno');
        }

        return $this->belongsTo(Usuario::class, 'id_modelo_dueno');
    }

    /**
     * Relación con el modelo Operador.
     */
    public function operador()
    {
        return $this->belongsTo(Operador::class, 'id_operador');
    }
}
