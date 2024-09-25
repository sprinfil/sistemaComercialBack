<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Letra extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id_convenio',
        'estado',
        'monto',
        'vigencia',
        'numero_letra'
    ];

    public function Convenio(): BelongsTo
    {
        return $this->belongsTo(Convenio::class, "id_convenio", "id");
    }

    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_origen', 'id_modelo');
    }

    public function cargosVigentes(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'origen', 'modelo_origen', 'id_origen')->where('estado', 'pendiente');
    }
}
