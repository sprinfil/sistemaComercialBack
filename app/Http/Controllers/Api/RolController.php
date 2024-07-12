<?php

namespace App\Http\Controllers\Api;

use App\Models\Rol;
use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Resources\RolResource;
use App\Http\Controllers\Controller;
use Spatie\Permission\Contracts\Role;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;
use Spatie\Permission\Models\Role as ModelsRole;

class RolController extends Controller
{
    //OBTENER LOS ROLES
    public function index()
    {
        return RolResource::collection(
            Rol::all()
        );
    }

    //GUARDAR
    public function store(StoreRolRequest $request)
    {
        $data = $request->validated();
        $data["guard_name"] = "web";
        $rol = Rol::create($data);
        return response(new RolResource($rol), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Rol $rol)
    {
        //
    }

    //ACTUALIZAR ROL
    public function update(UpdateRolRequest $request, Rol $rol)
    {
        $data = $request->validated();
        $rol = Rol::find($request["id"]);
        $rol->update($data);
        $rol->save();
        return new RolResource($rol);
    }

    //ELIMINAR ROL
    public function destroy(Request $request)
    {
        $rol = Rol::find($request["id"]);
        $rol->delete();
    }

    //DARLE PERMISOS A UN ROL
    public function give_rol_permissions(Request $request, string $id)
    {
        $rol = ModelsRole::find($id);
        $data = json_decode($request->getContent(), true);

        foreach ($data as $permission => $value) {
            $permission_temp = Permission::where("name", $permission)->first();
            $value === true ?
                $rol->givePermissionTo($permission_temp->name) :
                $rol->revokePermissionTo($permission_temp->name);
        }
        return json_encode($rol->getPermissionNames());
    }

    //OBTENER PERMISOS DE UN ROL
    public function get_all_permissions_by_rol_id(string $id)
    {
        $rol = ModelsRole::find($id);
        return json_encode($rol->getPermissionNames());
    }

    //ASIGNAR ROL A USUARIO
    public function assign_rol_to_user(string $user_id, string $rol_id){
        $rol = ModelsRole::find($rol_id);
        $user = User::find($user_id);
        $user->assignRole($rol->name);
        return $user->getRoleNames();
    }

    //QUTIAR ROL A USUARIO
    public function remove_rol_to_user(string $user_id, string $rol_id){
        $rol = ModelsRole::find($rol_id);
        $user = User::find($user_id);
        $user->removeRole($rol->name);
        return $user->getRoleNames();
    }
}
