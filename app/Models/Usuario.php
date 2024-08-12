<?php

namespace App\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Usuario extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "usuarios";
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
    

    public function datos_fiscales(): MorphOne
    {
        return $this->morphOne(DatoFiscal::class, 'origen', 'modelo', 'id_modelo');
    }

    public function cargos(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno');
    }
    public function cargosPendientes(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno')->where('estado','pendiente');
    }

    public function pagos(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno');
    }
    public function pagosPendientes(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno')->where('estado','pendiente');
    }

    public static function ConsultarPorNombres(string $usuario){
        $data = Usuario::whereRaw("
        CONCAT(
            COALESCE(nombre, ''), ' ', 
            COALESCE(apellido_paterno, ''), ' ', 
            COALESCE(apellido_materno, '')
        )  LIKE ?", ['%'.$usuario.'%'])->paginate(10);
          return $data;
    }
    public static function ConsultarPorNombreContacto(string $usuario){
        $data = Usuario::where('nombre_contacto','like','%'.$usuario.'%')->paginate(10);
          return $data;
    }
    public static function ConsultarTomas(string $usuario){
        $data=Usuario::find($usuario)->with('tomas')->paginate(10);
          return $data;
    }

    public static function ConsultarPorCurp(string $usuario){
        $data = Usuario::whereRaw("curp LIKE ?", ['%'.$usuario.'%'])->paginate(10);
          return $data;
    }

    public static function ConsultarPorCodigo(string $usuario){
        $data = Usuario::where("codigo_usuario",$usuario)->first();
          return $data;
    }

    public static function ConsultarPorRfc(string $usuario){
        $data = Usuario::whereRaw("rfc LIKE ?", ['%'.$usuario.'%'])->paginate(10);
          return $data;
    }

    public static function ConsultarPorCorreo(string $usuario){
        $data = Usuario::whereRaw("correo LIKE ?", ['%'.$usuario.'%'])->paginate(10);
          return $data;
    }

   /// puede que se borre, usar como prueba para consultas más complejas
   /*
    public static function ConsultarContratoPorUsuario(string $id_usuario){
        
        $data=Usuario::findOrFail($id_usuario);
        $contratos=$data->withWhereHas('contratos' , function (Builder $query) {
            $query->where('estatus', '!=','cancelado');
            
        })->get();
        return $contratos;
        
    }
        */

    public function contratoServicio($id_usuario){
        $usuario=usuario::find($id_usuario);
        $contrato=$usuario->contratoVigente;
        return $contrato;
    }

    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno;
    }
}