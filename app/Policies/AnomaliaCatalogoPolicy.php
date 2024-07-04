<?php

namespace App\Policies;

use App\Models\AnomaliaCatalogo;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AnomaliaCatalogoPolicy
{
    public function viewAny(User $user)
    {
        return $user->hasPermissionTo('Ver Anomalias');
    }

    public function view(User $user, AnomaliaCatalogo $post)
    {
        return $user->hasPermissionTo('Ver Anomalia');
    }

    public function create(User $user)
    {
        return $user->hasPermissionTo('Crear Anomalia');
    }

    public function update(User $user, AnomaliaCatalogo $post)
    {
        return $user->hasPermissionTo('Editar Anomalia');
    }

    public function delete(User $user, AnomaliaCatalogo $post)
    {
        return $user->hasPermissionTo('Eliminar Anomalia');
    }
}
