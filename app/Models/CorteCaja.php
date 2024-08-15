<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CorteCaja extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable=[
        'id_caja',
        'id_operador',
        'estatus',
        'total_registrado',
        'total_real',
        'total_efectivo_registrado',
        'total_efectivo_real',
        'total_tarjetas_registrado',
        'total_tarjetas_real',
        'total_cheques_registrado',
        'total_cheques_real',
        'discrepancia',
        'discrepancia_monto',
        'fecha_corte',
    ];
    public function pagos() {
        return $this->caja->pagos();
    }

    public function caja () : BelongsTo 
    {
        return $this->belongsTo(Caja::class , 'id_caja'); 
    }
    //Retiro de un corte de caja
    public function retiro() : HasMany {
        return $this->hasMany(RetiroCaja::class , 'id_corte_caja');
    }
}
