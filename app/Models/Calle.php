<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Calle extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre",
        "id_colonia"
    ];

    // Colonia asociada a la calle
    public function colonia() : BelongsTo
    {
        return $this->belongsTo(Colonia::class, 'id_colonia');
    }
    public function toma():HasMany
    {
        return $this->hasMany(Toma::class, 'calle');
    }
    public function contrato():HasMany
    {
        return $this->hasMany(Contrato::class, 'calle');
    }
    public function tomaEntreCalle1():HasMany
    {
        return $this->hasMany(Toma::class, 'entre_calle_1');
    }
    public function contratoEntreCalle1():HasMany
    {
        return $this->hasMany(Contrato::class, 'entre_calle_1');
    }
    public function tomaEntreCalle2():HasMany
    {
        return $this->hasMany(Toma::class, 'entre_calle_2');
    }
    public function contratoEntreCalle2():HasMany
    {
        return $this->hasMany(Contrato::class, 'entre_calle_2');
    }
}
