<?php

namespace App\Policies;

use App\Models\User;

class OperadorCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerOperadores');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerOperadores');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearOperador');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarOperador');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarOperador');
    }
}
