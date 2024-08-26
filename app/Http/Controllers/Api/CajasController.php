<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCajasRequest;
use App\Http\Requests\UpdateCajasRequest;
use App\Http\Resources\CajaResource;
use App\Models\Caja;
use App\Models\OperadorAsignado;
use App\Services\Caja\CajaService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CajasController extends Controller
{
    protected $cajaService;

    /**
     * Constructor del controller
     */
    public function __construct(CajaService $_cajaService)
    {
        $this->cajaService = $_cajaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            return response(CajaResource::collection(
                Caja::all()
            ),200);
        } catch(ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No fue posible consultar el pago'
            ], 500);
        }
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

    public function asignarOperador(Caja $cajas)
    {
        
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

    public function cargosPorCaja(Request $request)
    {
        try {
            return response($this->cajaService->cargoPorCaja($request));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudieron consultar los cargos de la caja'
            ], 500);
        }
    }

    public function pagosPorCaja(Request $request)
    {
        try {
            return response($this->cajaService->pagosPorCaja($request));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo consultar los pagos de esta caja'
            ], 500);
        }
    }

    public function solicitarCancelacionPago(Request $request)
    {
        try {
            return response($this->cajaService->solicitudCancelacionPago($request));
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo solicitar la cancelacion del pago'
            ], 500);
        }
    }
}
