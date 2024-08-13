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
        'id_caja',
    ];
    public function caja() : BelongsTo {
        return $this->belongsTo(Caja::class , "id_caja"); //ya
    }
    public function operador() : BelongsTo {
        return $this->belongsTo(Operador::class , "id_operador");//ya
    }
    

}
