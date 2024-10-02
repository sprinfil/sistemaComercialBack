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
        'cantidad_centavo_10',
        'cantidad_centavo_20',
        'cantidad_centavo_50',
        'cantidad_moneda_1',
        'cantidad_moneda_2',
        'cantidad_moneda_5',
        'cantidad_moneda_10',
        'cantidad_moneda_20',
        'cantidad_billete_20',
        'cantidad_billete_50',
        'cantidad_billete_100',
        'cantidad_billete_200',
        'cantidad_billete_500',
        'cantidad_billete_1000',
        'total_efectivo_registrado',
        'total_efectivo_real',
        'total_tarjetas_credito_registrado',
        'total_tarjetas_credito_real',
        'total_tarjetas_debito_registrado',
        'total_tarjetas_debito_real',
        'total_cheques_registrado',
        'total_cheques_real',
        'total_transferencias_registrado',
        'total_transferencias_real',
        'total_documentos_registrado',
        'total_documentos_real',
        'total_registrado',
        'total_real',
        'discrepancia',
        'discrepancia_monto',
        'descripcion',
        'fecha_corte',
    ];
    public function pagos() {
        return $this->caja->pagos();
    }

    public function caja () : BelongsTo 
    {
        return $this->belongsTo(Caja::class , 'id_caja'); 
    }
   
}
