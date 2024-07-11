<?php

namespace App\Policies;

use App\Models\User;

class DescuentoCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerDescuentos');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerDescuentos');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearDescuento');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarDescuento');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarDescuento');
    }
}
