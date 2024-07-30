<?php
namespace App\Services;

use App\Models\Usuario;
use Illuminate\Database\Eloquent\Collection;

class UsuarioService{

    public function store(array $usuario): Usuario{
        $usuario['codigo_usuario']=Usuario::max('codigo_usuario')+1;
        $user=Usuario::create($usuario);
        return $user;
    }
    public function ConsultaGeneral($codigoUsuario){
        $user=Usuario::where('codigo_usuario',$codigoUsuario)->with('tomas.medidor','tomas.giroComercial','contratos','descuento_asociado')->get();
        return $user;
    }
    public function TomasUsuario($id): Collection{
        $tomas=Usuario::find($id)->tomas;
        return $tomas;
    }

}