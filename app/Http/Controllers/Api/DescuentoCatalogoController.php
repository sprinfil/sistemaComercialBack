<?php

namespace App\Http\Controllers\Api;

use App\Models\DescuentoCatalogo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\DescuentoCatalogoResource;
use App\Http\Requests\StoreDescuentoCatalogoRequest;
use App\Http\Requests\UpdateDescuentoCatalogoRequest;
use App\Services\Catalogos\ConvenioCatalogoService;
use App\Services\Catalogos\DescuentoCatalogoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class DescuentoCatalogoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', DescuentoCatalogo::class);
        try{
            DB::beginTransaction();
            return (new DescuentoCatalogoService())->indexDescuentoCatalogoService();
            DB::commit();
        } catch(Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No fue posible consultar los descuentos'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDescuentoCatalogoRequest $request)
    {
        $this->authorize('create', DescuentoCatalogo::class);
       try {
         //Se valida el store
         $data = $request->validated();
         $nombre = $request->nombre;
         DB::beginTransaction();
         $descuento = (new DescuentoCatalogoService())->storeDescuentoCatalogoService($nombre,$data);
         DB::commit();
         return $descuento;
       } catch (Exception $ex) {
        DB::rollBack();
          return response()->json([
              'message' => 'Ocurrio un error al registrar el descuento.'
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
            $descuento = (new DescuentoCatalogoService())->showDescuentoCatalogoService($id);
            DB::commit();
            return $descuento;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo encontrar el descuento'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDescuentoCatalogoRequest $request, string $id)
    {
        $this->authorize('update', DescuentoCatalogo::class);
        try {

            $data = $request->validated();
            DB::beginTransaction();
            $descuento = (new DescuentoCatalogoService())->updateDescuentoCatalogoservice($data,$id);
            DB::commit();
            return $descuento;
           
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al editar el descuento'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', DescuentoCatalogo::class);
        try {
            DB::beginTransaction();
            $descuento = (new DescuentoCatalogoService())->destroyDescuentoCatalogoService($id);
            DB::commit();
            return $descuento;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al borrar el descuento.'
            ], 500);
        }
    }

    public function restaurarDato (DescuentoCatalogo $catalogoDescuento, Request $request)
    {
        try {
            $id = $request->id;
            DB::beginTransaction();
            $descuento = (new DescuentoCatalogoService())->restaurarDescuentoCatalogoService($id);
            DB::commit();
            return $descuento;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al restaurar el descuento.'
            ], 200); 
        }
    }
}
