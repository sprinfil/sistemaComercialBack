<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Factura extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_periodo",
        "id_toma",
        "id_consumo",
        "id_tarifa_servicio",
        "monto",
        "fecha",
    ];

     //Consumo asociado a la factura
     public function consumo () : BelongsTo
     {
         return $this->belongsTo(Consumo::class,'id_consumo');
     }
      //Toma asociada a la factura
      public function toma () : BelongsTo
      {
          return $this->belongsTo(Toma::class,'id_toma');
      }
      //Toma asociada a la factura
      public function periodo () : BelongsTo
      {
          return $this->belongsTo(Periodo::class,'id_periodo');
      }
      //Tarifa de servicio asociada a la factura
      public function tarifaServicio () : BelongsTo
      {
          return $this->belongsTo(TarifaServiciosDetalle::class,'id_tarifa_servicio');
      }
}
