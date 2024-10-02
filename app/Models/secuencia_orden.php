<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Secuencia_orden extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "secuencia_ordenes";
    protected $fillable = [
        "id_secuencia",
        "id_toma",
        "numero_secuencia"
    ];
    public function secuencia():BelongsTo{
        return $this->belongsTo(Secuencia::class,'id_secuencia');
    }
    public function toma():BelongsTo{
        return $this->belongsTo(Toma::class,'id_toma');
    }
}
