<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Consumo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_toma",
        "id_lectura_anterior",
        "id_lectura_actual",
        "consumo"
        
    ];


    //Toma a la que pertence el consumo
    public function toma() : BelongsTo
     {
         return $this->belongsTo(Toma::class, 'id_toma');
     }


      // Consumo que se registra en la lectura
      public function lectura() : HasOne
      {
          return $this->hasOne(Lectura::class, 'id_toma');
      }
       //Consumo asociado a la factura
     public function factura () : HasOne
     {
         return $this->hasOne(Factura::class,'id');
     }
}
