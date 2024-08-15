<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CajaCatalogo extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable=[
        'tipo_caja',
        'hora_apertura',
        'hora_cierre',
    ];


    public function caja () : HasMany
    {
        return $this->hasMany(Caja::class , 'id_caja_catalogo'); //ya
    }
    
}
