<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ConstanciaCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_concepto_catalogo",
        "nombre",
        "descripcion",
        "estado",
    ];

    public function constancia() : HasMany 
    {
        return $this->hasMany(Constancia::class , 'id'); 
    }

    public function conceptoCatalogo () : BelongsTo
    {
        return $this->belongsTo(ConceptoCatalogo::class , 'id_concepto_catalogo'); 
    }
}
