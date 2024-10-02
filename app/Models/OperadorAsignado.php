<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class OperadorAsignado extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable=[
        'id_operador',
        'id_caja_catalogo',
    ];
    //Relacion de caja asignada con la tabla operadorAsignado
    public function caja() : BelongsTo {
        return $this->belongsTo(CajaCatalogo::class , "id_caja_catalogo");//ya
    }
    //Relacion de operador con su caja asignada
    public function operador() : BelongsTo {
        return $this->belongsTo(Operador::class , "id_operador");//ya
    }
    

}
