<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CajaCatalogo extends Model
{
    use HasFactory;

    public function cajaCatalogo() : BelongsTo {
        return $this->belongsTo(Caja::class, 'id_caja');
    }
}
