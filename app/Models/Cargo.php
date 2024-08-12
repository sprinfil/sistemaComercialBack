<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cargo extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        "id_concepto",
        "concepto",
        "id_origen",
        "modelo_origen",
        "id_dueno",
        "modelo_dueno",
        "monto",
        "estado",
        "id_convenio",
        "fecha_cargo",
        "fecha_liquidacion",
    ];
    
    public function origen(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_origen', 'id_origen');
    }

    public function dueno(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_dueno', 'id_dueno');
    }

    public function abonos(): HasMany
    {
        return $this->hasMany(Abono::class,'id_cargo');
    }
    public function abonosTotal()
    {
       
    }
}