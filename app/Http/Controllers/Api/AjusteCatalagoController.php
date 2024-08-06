<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AjusteCatalogo;
use App\Http\Requests\StoreAjusteCatalogoRequest;
use App\Http\Requests\UpdateAjusteCatalogoRequest;
use App\Http\Resources\AjusteCatalogoResource;
use App\Services\Catalogos\AjusteCatalogoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjusteCatalagoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', AjusteCatalogo::class);
        try {
            DB::beginTransaction();
            $ajuste = (new AjusteCatalogoService())->indexAjusteCatalogoService();
            DB::commit();
            return $ajuste;
    
           } catch (Exception $ex) {
    
            DB::rollBack();
                return response()->json([
                    'message' => 'No se encontraron registros de ajustes.'
                ], 200);
           }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAjusteCatalogoRequest $request)
    {
        $this->authorize('create', AjusteCatalogo::class);
        try {
            $data = $request->validated();
            $nombre = $request->nombre;
            DB::beginTransaction();
            $ajuste = (new AjusteCatalogoService())->storeAjusteCatalogoService($nombre,$data);
            DB::commit();
            return $ajuste;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al registrar el ajuste.'
            ], 200);
       }
        
    
    }

    /**
     * Display the specified resource.
     */
    public function show(AjusteCatalogo $ajusteCatalogo)
    {
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAjusteCatalogoRequest $request, AjusteCatalogo $ajusteCatalogo)
    {
        $this->authorize('update', AjusteCatalogo::class);
        try {
            $data = $request->validated();
            $id = $request->id;
            DB::beginTransaction();
            $ajuste = (new AjusteCatalogoService())->updateAjusteCatalogoservice($data,$id);
            DB::commit();
            return $ajuste;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al modificar el ajuste.'
            ], 200);
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(AjusteCatalogo $ajusteCatalogo, Request $request)
    {
        $this->authorize('delete', AjusteCatalogo::class);
        try {
            $id = $request->id;
            DB::beginTransaction();
            $ajuste = (new AjusteCatalogoService())->destroyAjusteCatalogoService($id);
            DB::commit();
            return response()->json([
                'message' => 'Se ha eliminado el ajuste.'
            ], 200);
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al eliminar el ajuste.'
            ], 200);
        }
        
    }

    public function restaurarDato (AjusteCatalogo $convenioCatalogo, Request $request)
    {
        try {
            $id = $request->id;
            $ajuste = (new AjusteCatalogoService())->restaurarAjusteCatalogoServicio($id);
            return $ajuste;
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al restaurar el ajuste.'
            ], 200);
        }
        
    }
}
