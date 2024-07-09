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
    public static function ConsultarPorCurp(string $usuario){
        $data = Usuario::whereRaw("curp LIKE ?", ['%'.$usuario.'%'])->get();
          return $data;
    }
    public static function ConsultarPorRfc(string $usuario){
        $data = Usuario::whereRaw("rfc LIKE ?", ['%'.$usuario.'%'])->get();
          return $data;
    }
    public static function ConsultarPorCorreo(string $usuario){
        $data = Usuario::whereRaw("correo LIKE ?", ['%'.$usuario.'%'])->get();
          return $data;
    }
   
    public static function ConsultarContratoPorNombre(string $id_usuario){
        
        $data=Usuario::findOrFail($id_usuario);
        $data=$data->withWhereHas('contratos' , function (Builder $query) {
            $query->where('estatus', '!=','cancelado');
            
        })->get();
        return $data;
        
    }
}
