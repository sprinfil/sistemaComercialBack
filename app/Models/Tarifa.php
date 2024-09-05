<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Tarifa extends Model
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
        return $this->hasMany(TarifaServiciosDetalle::class, 'id_tarifa_servicio');
    }
    public function conceptos()
    {
        return TarifaConceptoDetalle::all();
    }
    public function tarifaServicio() : HasMany {
        return $this->hasMany(TarifaServicio::class , 'id_tarifa', 'id');
    }
}
