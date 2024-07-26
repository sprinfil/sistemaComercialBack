<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class corteCaja extends Model
{
    use HasFactory, SoftDeletes;
    
   
    public function pago() : BelongsTo {
        return $this->belongsTo(pago::class , 'id_pago'); 
    }

    public function caja () : BelongsTo 
    {
        return $this->belongsTo(Caja::class , 'id_caja'); 
    }
    //Retiro de un corte de caja
    public function retiro() : HasMany {
        return $this->hasMany(retiroCaja::class , 'id_corte_caja');
    }
}
