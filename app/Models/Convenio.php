<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Convenio extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_convenio_catalogo',
        'monto_conveniado',
        'monto_total',
        'periodicidad',
        'cantidad_letras',
        'estado',
        'comentario',
    ];

    public function ConvenioCatalogo() : BelongsTo
    {
        return $this->belongsTo(ConvenioCatalogo::class, "id_convenio_catalogo", "id");
    }

    public function Letra() : HasMany
    {
        return $this->hasMany(Letra::class,'id_convenio');
    }

    public function CargosConveniados() : HasMany
    {
        return $this->hasMany(CargosConveniado::class,'id_convenio');
    }
}