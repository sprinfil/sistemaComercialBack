<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ConstanciaCatalogo;
use App\Http\Requests\StoreCosntanciaCatalogoRequest;
use App\Http\Requests\UpdateCosntanciaCatalogoRequest;
use App\Http\Resources\ConstanciaCatalogoResource;
use App\Services\Catalogos\ConstanciaCatalogoService;
use Exception;
use Illuminate\Support\Facades\DB;

class ConstanciaCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', ConstanciaCatalogo::class);
        try {
            DB::beginTransaction();
            return (new ConstanciaCatalogoService())->indexconstanciaCatalogoService();
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'No se encontraron registros de constancias.'
            ], 200);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCosntanciaCatalogoRequest $request)
    {
        $this->authorize('create', ConstanciaCatalogo::class);
        try {
            $data = $request->validated();
            $nombre = $request->nombre;
            DB::beginTransaction();
            $constancia = (new ConstanciaCatalogoService())->storeConstanciaCatalogoService($nombre,$data);
            DB::commit();
            return $constancia;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al registrar la constancia.'
            ], 200);
        }
       
       
    }

    /**
     * Display the specified resource.
     */
    public function show(ConstanciaCatalogo $cosntanciaCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCosntanciaCatalogoRequest $request, ConstanciaCatalogo $cosntanciaCatalogo)
    {
        $this->authorize('update', ConstanciaCatalogo::class);
        try {
            $data = $request->validated();
            $id = $request->id;
            DB::beginTransaction();
            $constancia = (new ConstanciaCatalogoService())->updateAjusteCatalogoservice($data,$id);
            DB::commit();
            return $constancia;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al registrar la constancia.'
            ], 200);
        }
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConstanciaCatalogo $cosntanciaCatalogo, Request $request)
    {
        $this->authorize('delete', ConstanciaCatalogo::class);
        try {
            $id = $request->id;
            DB::beginTransaction();
            $constancia = (new ConstanciaCatalogoService())->destroyAjusteCatalogoService($id);
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al eliminar la constancia.'
            ], 200);
        }
        
    }

    public function restaurarDato (ConstanciaCatalogo $constanciaCatalogo, Request $request)
    {
        try {
            $id = $request->id;
            DB::beginTransaction();
            $constancia = (new ConstanciaCatalogoService())->restaurarConstanciaCatalogoServicio($id);
            DB::commit();
            return response()->json([
                'message' => 'Se ha restaurado la constancia.'
            ]);
        } catch (Exception $th) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al restaurar la constancia.'
            ], 200);
        }
       
    }
}
