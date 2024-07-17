<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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
}
