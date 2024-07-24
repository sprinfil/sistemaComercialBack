<?php
namespace App\Services;

use App\Models\Usuario;

class UsuarioService{

    public function store(array $usuario): Usuario{
        $usuario['codigo_operador']=Usuario::max('codigo_operador')+1;
        $user=Usuario::create($usuario);
        return $user;
    }

}