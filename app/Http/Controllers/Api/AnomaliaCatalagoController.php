<?php

namespace App\Http\Controllers\Api;

use App\Policies\AnomaliaCatalogoPolicy;
use Illuminate\Http\Request;
use App\Models\AnomaliaCatalogo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\AnomaliaCatalogoResource;
use App\Http\Requests\StoreAnomaliaCatalogoRequest;
use App\Http\Requests\UpdateAnomaliaCatalogoRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class AnomaliaCatalagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        //$this->authorize('viewAny', AnomaliaCatalogo::class);
         
        return AnomaliaCatalogoResource::collection(
            AnomaliaCatalogo::all()
        );

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnomaliaCatalogoRequest $request)
    {
        //$this->authorize('create', AnomaliaCatalogo::class);

        $data = $request->validated();
        $anomalia = AnomaliaCatalogo::create($data);
        return response(new AnomaliaCatalogoResource($anomalia), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $anomalia = AnomaliaCatalogo::findOrFail($id);
            return response(new AnomaliaCatalogoResource($anomalia), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la anomalia'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnomaliaCatalogoRequest $request)
    {
        $data = $request->validated();
        $anomalia = AnomaliaCatalogo::find($request["id"]);
        $anomalia->update($data);
        $anomalia->save();
        return new AnomaliaCatalogoResource($anomalia);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $anomalia = AnomaliaCatalogo::find($request["id"]);
        $anomalia->delete();
    }
}
