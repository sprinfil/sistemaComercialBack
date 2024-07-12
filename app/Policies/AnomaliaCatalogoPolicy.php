<?php

namespace App\Policies;

use App\Models\AnomaliaCatalogo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AnomaliaCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('VerAnomalias');
    }

    public function view(User $user)
    {
        return $user->hasPermissionTo('VerAnomalias');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('CrearAnomalia');
    }

    public function update(User $user)
    {
        return $user->hasPermissionTo('EditarAnomalia');
    }

    public function delete(User $user)
    {
        return $user->hasPermissionTo('EliminarAnomalia');
    }
}
