<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Operador;
use App\Http\Requests\StoreOperadorRequest;
use App\Http\Requests\UpdateOperadorRequest;
use App\Http\Resources\OperadorResource;
use App\Models\User;
use App\Services\Catalogos\OperadorCatalogoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OperadorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $this->authorize('viewAny', Operador::class);
        try {
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->indexOperadorCatalogoService();
            return $operador;
            DB::commit();
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No fue posible consultar los operadores'
            ], 500);
        }

        
        

    }

    public function store_2(StoreOperadorRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->storeOperadorCatalogoService_2($data);
            DB::commit();
            return $operador;
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo guardar el operador.'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Operador $operador, StoreOperadorRequest $request)
    {
        $this->authorize('create', Operador::class);

        try {
            //VALIDA EL STORE
            $data = $request->validated();           
            $codEmpleado = $request->codigo_empleado;
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->storeOperadorCatalogoService($codEmpleado,$data);
            DB::commit();
            return $operador;
          
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo guardar el operador'
            ], 500);
        }
        
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->showOperadorCatalogoService($id);
            DB::commit();
            return $operador;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se pudo encontrar el operador'
            ], 500);
        }
        //
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(UpdateOperadorRequest $request, string $id)
    {
        $this->authorize('update', Operador::class);
        
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->updateOperadorCatalogoservice($data,$id);
            DB::commit();
            return $operador;
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se edito el operador'
            ], 500);
        }
    }

    public function update_2(UpdateOperadorRequest $request, string $id_user, string $id_operador)
    {
        
            
           try {
            $data = json_decode($request->getContent(), true);
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->updateOperadorCatalogoservice_2($data, $id_user, $id_operador);
            DB::commit();
            return $operador;
           } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'message' => 'Ocurrio un error al modificar el operador.'
            ], 200);
           }

           
        
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $this->authorize('delete', Operador::class);
        try {
            DB::beginTransaction();
           $operador = (new OperadorCatalogoService())->destroyOperadorCatalogoService($id);
           DB::commit();
           return $operador;
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al borrar el operador'
            ], 500);
        }
        //
    }
    public function restaurarOperador(Operador $operador, HttpRequest $request)
    {

        try {
            $id = $request->id;
            DB::beginTransaction();
            $operador = (new OperadorCatalogoService())->restaurarOperadorCatalogoService($id);
            DB::commit();
            return $operador;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([              
                'message' => 'Ocurrio un error al restaurar el operador.'
            ], 200);  
        }

       
    }
}
