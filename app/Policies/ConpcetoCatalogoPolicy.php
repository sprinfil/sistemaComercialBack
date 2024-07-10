<?php

namespace App\Policies;

use App\Models\User;

class ConpcetoCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerConceptos');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerConceptos');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearConcepto');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarConcepto');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarConcepto');
    }
}
