<?php

namespace App\Http\Controllers\Api;

use App\Models\ConvenioCatalogo;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConvenioResource;
use App\Http\Requests\StoreConvenioCatalogoRequest;
use App\Http\Requests\UpdateConvenioCatalogoRequest;
use App\Services\Catalogos\ConvenioCatalogoService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use function PHPUnit\Framework\returnValueMap;

class ConvenioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', ConvenioCatalogo::class);
        try {
            DB::beginTransaction();
            $convenio = (new ConvenioCatalogoService())->indexconvenioCatalogoService();
            DB::commit();
            return $convenio;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'No se encontraron registros de convenios.'
            ], 200);
        }
        
       

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConvenioCatalogoRequest $request)
    {
        $this->authorize('create', ConvenioCatalogo::class);      
       //Se valida el store      
       try {
         $data = $request->validated();
         $nombre = $request->nombre;
         DB::beginTransaction();
         $convenio = (new ConvenioCatalogoService())->storeConvenioCatalogoService($nombre, $data);
         DB::commit();
         return $convenio;
       } catch (Exception $ex) {
         DB::rollBack();
         return response()->json([
             'message' => 'Ocurrio un error al registrar el convenio.'
         ], 200);
       }
       
    }

    /**
     * Display the specified resource.
     */
    public function show(ConvenioCatalogo $convenioCatalogo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConvenioCatalogoRequest $request, ConvenioCatalogo $convenioCatalogo)
    {
        $this->authorize('update', ConvenioCatalogo::class);
        try {
            $data = $request->validated();
            $id = $request->id;
            DB::beginTransaction();
            $convenio = (new ConvenioCatalogoService())->updateConvenioCatalogoservice($data,$id);
            DB::commit();
            return $convenio;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al registrar el convenio.'
            ], 200);
            
        }
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConvenioCatalogo $convenioCatalogo, Request $request)
    {
        $this->authorize('delete', ConvenioCatalogo::class);
        try
        {
           $id = $request->id;
           DB::beginTransaction();
           $convenio = (new ConvenioCatalogoService())->destroyConvenioCatalogoService($id);
           DB::commit();
           return $convenio;
        }
        catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Algo fallo'], 500);
        }

    }

    public function restaurarDato (ConvenioCatalogo $convenioCatalogo, Request $request)
    {
        try {
            $id = $request->id;
            DB::beginTransaction();
            $convenio = (new ConvenioCatalogoService())->restaurarConstanciaCatalogoServicio($id);
            DB::commit();
            return $convenio;
        } catch (\Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al restaurar la constancia.'
            ], 200); 
        }
        
    }
}
