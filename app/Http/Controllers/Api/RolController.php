<?php

namespace App\Http\Controllers\Api;

use App\Models\Rol;
use Illuminate\Http\Request;
use App\Http\Resources\RolResource;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRolRequest;
use App\Http\Requests\UpdateRolRequest;

class RolController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return RolResource::collection(
            Rol::all()
        );
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRolRequest $request, Rol $rol)
    {
        $data = $request->validated();
        $rol = Rol::find($request["id"]);
        $rol->update($data);
        $rol->save();
        return new RolResource($rol);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $anomalia = Rol::find($request["id"]);
        $anomalia->delete();
    }
}
