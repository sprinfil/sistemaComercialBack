<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TipoToma extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        "nombre",
        "descripcion",
        "estado"
    ];
    #TODO
    /*
    public function tomas(): HasMany
    {
        return $this->hasMany(Toma::class, 'id_tipoToma');
    }
        */
    public static function ConsultarPorNombre(string $tipoToma){
        $data = TipoToma::where('nombre','like' ,'%'.$tipoToma.'%')->get();
          return $data;
    }

    // Tomas asociadas a tipo de toma
    public function tomas() : HasMany
    {
        return $this->hasMany(Toma::class, 'id_tipo_toma');
    }

    public function tarifaServicio() : HasMany {
        return $this->hasMany(TarifaServicio::class, 'id_tipo_toma');
    }
}
