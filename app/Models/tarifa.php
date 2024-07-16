<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class tarifa extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "tarifas";

    protected $fillable = [
        "nombre",
        "descripcion",
        "fecha",
        "estado"
       
    ];
    public function servicio() : HasMany
    {
        return $this->hasMany(Servicio::class, 'id_tarifa_concepto_detalle');
    }
}