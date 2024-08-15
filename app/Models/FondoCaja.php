<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class fondoCaja extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        'id_caja',
        'monto',
    ];

    public function fondoCaja() : BelongsTo {
        return $this->belongsTo(Caja::class, 'id_fondo_caja'); 
    }
}
