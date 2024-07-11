<?php

namespace App\Policies;

use App\Models\User;

class BonificacionCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerBonificaciones');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerBonificaciones');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearBonificacion');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarBonificacion');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarBonificacion');
    }
}
