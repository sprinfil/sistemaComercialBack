<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCajaCatalogoRequest;
use App\Http\Requests\StoreCajasRequest;
use App\Http\Requests\StoreOperadorAsignadoRequest;
use App\Http\Requests\UpdateCajaCatalogoRequest;
use App\Http\Requests\UpdateCajasRequest;
use App\Http\Resources\CajaResource;
use App\Models\Caja;
use App\Models\CajaCatalogo;
use App\Models\OperadorAsignado;
use App\Services\Caja\CajaService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        /*
        try{
            return response(CajaResource::collection(
                Caja::all()
            ),200);
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No fue posible consultar el pago'
            ], 500);
        }
            */
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCajasRequest $request)
    {
        try {
            $data = $request->validated();
        
            DB::beginTransaction();
            $apertura = (new CajaService())->iniciarCaja($data);
            DB::commit();
            return $apertura;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al iniciar la caja.'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Caja $cajas)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCajasRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $corte = (new CajaService())->corteCaja($data);
            DB::commit();
            return $corte;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error al realizar el cierre de caja.'
            ], 500);
        }
        
    }

    public function asignarOperador(StoreOperadorAsignadoRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        $operadorAsignado = (new CajaService())->asignarOperadorService($data['operadores_asignados']);
        DB::rollBack();
        return $operadorAsignado;
        try {
           
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la asignacion del operador.'
            ], 500);
        }
    }

    public function retirarAsignacion (StoreOperadorAsignadoRequest $request)
    {
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $asignacionRetirada = (new CajaService())->retirarAsignacionService($data);
            DB::commit();
            return $asignacionRetirada;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la asignacion del operador.'
            ], 500);
        }
    }

    public function consultarCajas()
    {
        try {
            DB::beginTransaction();
            $cajas = (new CajaService())->consultarCajasCatalogo();
            DB::commit();
            return $cajas;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de cajas.'
            ], 500);
        }
    }

    public function guardarCajaCatalogo(StoreCajaCatalogoRequest $request)
    {
        $data = $request->validated();
        DB::beginTransaction();
        $cajaCatalogo = (new CajaService())->guardarCajaCatalogoService($data);
        DB::commit();
        return $cajaCatalogo;
        try {
           
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante el registro de la caja.'
            ], 500);
        }
    }

    public function eliminarCajaCatalogo(string $id)
    {
        try {
            DB::beginTransaction();
            $caja = (new CajaService())->eliminarCajaCatalogoService($id);
            DB::commit();
            return $caja;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante el registro de la caja.'
            ], 500);
        }
    }

    public function restaurarCajaCatalogo(string $id)
    {
        try {
            DB::beginTransaction();
            $caja = (new CajaService())->restaurarCajaCatalogoService($id);
            DB::commit();
            return $caja;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la restauracion de la caja.'
            ], 500);
        }
    }

    public function modificarCajaCatalogo(UpdateCajaCatalogoRequest $request, string $id)
    {
        
        try {
            $data = $request->validated();
            DB::beginTransaction();
            $caja = (new CajaService())->modificarCajaCatalogoService($data, $id);
            DB::commit();
            return $caja;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la modificacion de la caja.'
            ], 500);
        }
    }

    public function mostrarCaja(string $id)
    {
        try {
            DB::beginTransaction();
            $caja = (new CajaService())->mostrarCajaService($id);
            DB::commit();
            return $caja;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'Ocurrio un error durante la busqueda de la caja.'
            ], 500);
        }
    }

    public function buscarSesionCaja(Request $request)
    {
        try {
            DB::beginTransaction();
            $cajaSesion = (new CajaService())->buscarSesionCajaService($request);
            DB::commit();
            return response()->json(["operador"=>$cajaSesion]) ;
        } catch (Exception $ex) {
            DB::rollBack();
            return response()->json([
                'error' => 'No se encontro sesion activa de la caja asociada a este operador.'
            ], 500);
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Caja $cajas)
    {
        //
    }

     /**
     * Display a listing of the resource.
     */
    public function cargosPorModelo (Request $request)
    {
        // si es por 
    }

    public function test()
    {
        $pagos = Caja::find(1)->pagosPorTipo('efectivo');
    }
}
