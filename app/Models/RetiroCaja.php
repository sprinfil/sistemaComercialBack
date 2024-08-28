<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RetiroCaja extends Model
{
    
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_sesion_caja",
        "cantidad_centavo_10",
        "cantidad_centavo_20",
        "cantidad_centavo_50",
        "cantidad_moneda_1",
        "cantidad_moneda_2",
        "cantidad_moneda_5",
        "cantidad_moneda_10",
        "cantidad_moneda_20",
        "cantidad_billete_20",
        "cantidad_billete_50",
        "cantidad_billete_100",
        "cantidad_billete_200",
        "cantidad_billete_500",
        "cantidad_billete_1000",
        "monto_total",
    ];

    public function retiroCaja() : BelongsTo {
        return $this->belongsTo(Caja::class , 'id_sesion_caja');
    }
}
