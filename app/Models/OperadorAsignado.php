<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OperadorAsignado extends Model
{
    use HasFactory;
    
    public function caja() : BelongsTo {
        return $this->belongsTo(caja::class , "id_caja");
    }
    public function operador() : BelongsTo {
        return $this->belongsTo(Operador::class , "id_operador");
    }
}
