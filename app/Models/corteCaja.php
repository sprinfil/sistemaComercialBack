<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class corteCaja extends Model
{
    use HasFactory, SoftDeletes;

    public function pago () : HasOne
    {
        return $this->hasOne(Pago::class, 'id');
    }
    public function retiroCaja() : BelongsTo {
        return $this->belongsTo(retiroCaja::class, 'id_corte_caja');
    }
}
