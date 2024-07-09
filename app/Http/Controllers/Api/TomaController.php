<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Toma;
use App\Http\Requests\StoreTomaRequest;
use App\Http\Requests\UpdateTomaRequest;
use App\Http\Resources\TomaResource;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class TomaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response( TomaResource::collection(
            Toma::all()
        ),200);
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTomaRequest $request)
    {
        try{
            //VALIDA EL STORE
             $data = $request->validated();
             $toma = Toma::create($data);
        return response(new TomaResource ($toma), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la toma'.$e
            ], 500);
        }
         
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $toma = Toma::findOrFail($id);
            return response(new TomaResource($toma), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la toma'
            ], 500);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTomaRequest $request, string $id)
    {
        try {
            $data = $request->validated();
            $toma = Toma::findOrFail($id);
            $toma->update($data);
            $toma->save();
            return response(new TomaResource($toma), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la toma'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $toma = Toma::findOrFail($id);
            $toma->delete();
            return response("Toma eliminada con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo eliminar la toma'
            ], 500);
        }
    }

   
}
