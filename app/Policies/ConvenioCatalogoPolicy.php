<?php

namespace App\Policies;

use App\Models\User;

class ConvenioCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerConvenios');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerConvenios');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearConvenio');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarConvenio');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarConvenio');
    }
}
