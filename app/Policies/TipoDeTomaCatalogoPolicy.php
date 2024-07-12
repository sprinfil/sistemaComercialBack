<?php

namespace App\Policies;

use App\Models\User;

class TipoDeTomaCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerTiposDeToma');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerTiposDeToma');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearTipoDeToma');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarTipoDeToma');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarTipoDeTomas');
    }
}
