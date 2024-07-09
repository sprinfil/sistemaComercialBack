<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'nombre_contacto',
        'telefono',
        'curp',
        'rfc',
        'correo',
    ];
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class, 'id_usuario');
    }
    public static function ConsultarPorNombres(string $usuario){
        $data = Usuario::whereRaw("
        CONCAT(
            COALESCE(nombre, ''), ' ', 
            COALESCE(apellido_paterno, ''), ' ', 
            COALESCE(apellido_materno, '')
        )  LIKE ?", ['%'.$usuario.'%'])->get();
          return $data;
    }
   
    public static function ConsultarContratoPorNombre(string $usuario){
        $data = Usuario::ConsultarPorNombres($usuario);
        $data=$data->load(['contratos' => function (Builder $query) {
            $query->where('estatus', '!=','cancelado');
        }])->all();
          return $data;
    }
}
