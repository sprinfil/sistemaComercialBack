<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class GiroComercialCatalogo extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "nombre",
        "descripcion",
    ];

    public function tomas() : HasMany
    {
        return $this->hasMany(Toma::class, 'id_giro_comercial');
    }
}
