<?php

namespace App\Models;

use Hamcrest\Type\IsNumeric;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Contracts\Database\Query\Builder as QueryBuilder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Ramsey\Uuid\Type\Decimal;

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
    public function cargosVigentes(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno')->where('estado','pendiente')->with('concepto');
    }
    public function cargosVigentesConConcepto(): MorphMany
    {
        return $this->morphMany(Cargo::class, 'dueno', 'modelo_dueno', 'id_dueno')
                    ->where('estado', 'pendiente')
                    ->with('concepto'); // Cargar la relación 'concepto' junto con los cargos
    }
    
    public function saldoCargosUsuario(){
        $total=0;
        $cargos=$this->cargosVigentes;
        foreach ($cargos as $cargo){
            $total+=$cargo->monto;
            $abonos=$cargo->abonos;
            foreach ($abonos as $abono){
                $total-=$abono->total_abonado;
            }

        }
        return $total;
    }

    /*public function saldoPendiente(){
        $total=0;
        $cargos=$this->cargosVigentes;
        foreach ($cargos as $cargo){
            $total+=$cargo->monto;
            $abonos=$cargo->abonos;
            foreach ($abonos as $abono){
                $total-=$abono->total_abonado;
            }

        }
        return $total;
    }*/

    public function saldoPendiente(){
        $total_final = 0;
        $cargos_pendientes = $this->cargosVigentes;
        foreach($cargos_pendientes as $cargo)
        {
            $total_final += $cargo->montoPendiente();
        }
        return $total_final;
    }

    public function saldoSinAplicar(){
        $total_final = 0;
        $pagos_pendientes = $this->pagosPendientes;
        foreach($pagos_pendientes as $pago)
        {
            $total_final += $pago->pendiente();
        }
        return $total_final;
    }

    public function pagos(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno');
    }
    public function pagosPendientes(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno')->where('estado','pendiente');
    }
    public function pagosConDetalle(): MorphMany
    {
        return $this->morphMany(Pago::class, 'dueno', 'modelo_dueno', 'id_dueno')
                    ->with(['abonosConCargos']);
    }
    
  
    public static function ConsultarPorNombresCodigo(string $usuario){
        if (is_numeric($usuario)){
            return Usuario::ConsultarPorCodigo($usuario);
        }
        else{
            $nuvUsuario=str_replace(" ","%",$usuario);
            $data = Usuario::with(["tomas.ordenesTrabajo"=> function($query){
                $query->where('estado','No asignada');
              }])
              ->whereRaw("
            CONCAT(
                COALESCE(nombre, ''), ' ', 
                COALESCE(apellido_paterno, ''), ' ', 
                COALESCE(apellido_materno, '')
            )  LIKE ?", ['%'.$nuvUsuario.'%'])->paginate(10);
              return $data;
        }
       
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
        $data = Usuario::with(["tomas.ordenesTrabajo"=> function($query){
          $query->where('estado','No asignada');
        }])
        ->where("codigo_usuario",$usuario)->paginate(10);
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