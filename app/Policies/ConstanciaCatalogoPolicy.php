<?php

namespace App\Policies;

use App\Models\User;

class ConstanciaCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerConstancias');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerConstancias');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearConstancia');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarConstancia');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarConstancia');
    }
}
