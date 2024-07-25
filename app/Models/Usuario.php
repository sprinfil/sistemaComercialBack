<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'codigo_usuario',
        'nombre',
        'apellido_paterno',
        'apellido_materno',
        'nombre_contacto',
        'telefono',
        'curp',
        'rfc',
        'correo',
    ];

    // Contratos asociados al usuario
    public function contratos(): HasMany
    {
        return $this->hasMany(Contrato::class, 'id_usuario');
    }
    public function contratoVigente(): hasMany
    {
        return $this->hasMany(Contrato::class, 'id_usuario')->where('estatus','!=','cancelado');
    }
      // Tomas asociadas al usuario
    public function tomas() : HasMany
    {
        return $this->hasMany(Toma::class, 'id_usuario');
    }
    public function descuento_asociado() : HasOne
    {
        return $this->hasOne(DescuentoAsociado::class, 'id_usuario');
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
    public static function ConsultarTomas(string $usuario){
        $data=Usuario::find($usuario)->with('tomas');
          return $data;
    }
    public static function ConsultarPorCurp(string $usuario){
        $data = Usuario::whereRaw("curp LIKE ?", ['%'.$usuario.'%'])->get();
          return $data;
    }
    public static function ConsultarPorCodigo(string $usuario){
        $data = Usuario::where("codigo_usuario",$usuario)->get();
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

   /// puede que se borre, usar como prueba para consultas más complejas
    public static function ConsultarContratoPorUsuario(string $id_usuario){
        
        $data=Usuario::findOrFail($id_usuario);
        $contratos=$data->withWhereHas('contratos' , function (Builder $query) {
            $query->where('estatus', '!=','cancelado');
            
        })->get();
        return $contratos;
        
    }
    public function contratoServicio($id_usuario){
        $usuario=usuario::find($id_usuario);
        $contrato=$usuario->contratoVigente;
        return $contrato;
    }

  
}
