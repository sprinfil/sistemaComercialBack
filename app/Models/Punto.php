<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Punto extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        "id_asignacion_geografica",
        "latitud",
        "longitud"
    ];

    public function asignacion():BelongsTo{
        return $this->belongsTo(BelongsTo::class,'id_asignacion_geografica');;
    }
}
