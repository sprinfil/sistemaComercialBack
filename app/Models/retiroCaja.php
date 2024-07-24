<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class retiroCaja extends Model
{
    use HasFactory, SoftDeletes;

    public function retiroCaja() : BelongsTo {
        return $this->belongsTo(corteCaja::class , 'id_retiro_caja');
    }
}
