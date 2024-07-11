<?php

namespace App\Policies;

use App\Models\User;

class AjusteCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerAjustes');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerAjustes');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearAjuste');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarAjuste');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarAjuste');
    }
}
