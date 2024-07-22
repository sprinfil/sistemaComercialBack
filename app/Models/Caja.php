<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caja extends Model
{
    use HasFactory, SoftDeletes;
    //Caja con pagos
    public function pagos () : HasMany
    {
        return $this->hasMany(Pago::class , 'id');
    }
    //Corte de caja con cajas
    public function corteCaja() : HasMany {
        return $this->hasMany(corteCaja::class , 'id_');
    }
    //Fondo de una caja
    public function fondoCaja() : HasOne {
        return $this->hasOne(fondoCaja::class, 'id_caja');
    }
  

}
