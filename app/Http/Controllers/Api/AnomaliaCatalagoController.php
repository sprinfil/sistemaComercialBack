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
use App\Services\Catalogos\AnomaliaCatalogoService as CatalogosAnomaliaCatalogoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class AnomaliaCatalagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
       $this->authorize('viewAny', AnomaliaCatalogo::class);

       try {
        DB::beginTransaction();
        $anomalia = (new CatalogosAnomaliaCatalogoService())->indexAnomaliaCatalogo();
        DB::commit();
        return $anomalia;

       } catch (Exception $ex) {

        DB::rollBack();
            return response()->json([
                'message' => 'No se encontraron registros de anomalias.'
            ], 200);
       }

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAnomaliaCatalogoRequest $request)
    {
       $this->authorize('create', AnomaliaCatalogo::class);
       
       try {
        DB::beginTransaction();
        $data=$request->validated();
        $anomalia = (new CatalogosAnomaliaCatalogoService())->storeAnomaliaCatalogo($data);     

        DB::commit();
        return $anomalia;

       } catch (Exception $ex) {
        DB::rollBack();

            return response()->json([
                'message' => 'La anomalia no se pudo registar.'
            ], 200);
       }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            DB::beginTransaction();
            $anomalia = (new CatalogosAnomaliaCatalogoService())->showAnomaliaCatalogo($id);

            DB::commit();
            return $anomalia;

        } catch (Exception $ex) {

            DB::rollBack();
            return response()->json([
                'error' => 'No se ha encontrado la anomalia.'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAnomaliaCatalogoRequest $request)
    {
        $this->authorize('update', AnomaliaCatalogo::class);
        
        try {

            $data=$request->validated();
            $id = $request['id'];
            DB::beginTransaction();
            $anomalia = (new CatalogosAnomaliaCatalogoService())->updateAnomaliaCatalogo($data,$id); 

            DB::commit();
            return $anomalia;

        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se ha actualizado la anomalia.'
            ], 500);
        }
       
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $this->authorize('delete', AnomaliaCatalogo::class);
        try {
            $id = $request["id"];
            DB::beginTransaction();

            $anomalia = (new CatalogosAnomaliaCatalogoService())->destroyAnomaliaCatalogo($id);
            DB::commit();
        } catch (Exception $ex) {

            DB::rollBack();
            return response()->json([
                'error' => 'No se ha eliminado la anomalia.'
            ], 500);
        }
        
    }

    public function restaurarDato (AnomaliaCatalogo $catalogoAnomalia, Request $request)
    {
        try {
           $id = $request->id;
           DB::beginTransaction();
           $anomalia = (new CatalogosAnomaliaCatalogoService())->restaurarAnomaliaCatalogo($id);
           DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se ha restaurado la anomalia.'
            ], 500);
        }
        
    }
}
