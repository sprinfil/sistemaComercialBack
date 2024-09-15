<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Colonia extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre"
    ];

    // Calles de la colonia
    public function calles() : HasMany
    {
        return $this->hasMany(Calle::class, 'id_colonia');
    }
    public function toma():HasMany
    {
        return $this->hasMany(Toma::class, 'colonia');
    }
    public function contrato():HasMany
    {
        return $this->hasMany(Contrato::class, 'colonia');
    }
}