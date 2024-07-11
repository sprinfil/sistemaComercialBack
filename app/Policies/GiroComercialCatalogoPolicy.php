<?php

namespace App\Policies;

use App\Models\User;

class GiroComercialCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerGirosComerciales');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerGirosComerciales');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearGiroComercial');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarGiroComercial');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarGircoComercialP');
    }
}
