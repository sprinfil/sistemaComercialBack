<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factibilidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'factibilidad';

    protected $fillable = 
    [
        'estado_factible',
        'derechos_conexion'
    ];

    public function contrato () : BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function toma () : BelongsTo
    {
        return $this->belongsTo(Toma::class);
    }

}
