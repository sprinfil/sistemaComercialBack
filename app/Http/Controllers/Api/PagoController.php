<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePagoRequest;
use App\Http\Resources\PagoResource;
use App\Services\PagoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PagoController extends Controller
{
    protected $pagoService;

    /**
     * Constructor del controller
     */
    public function __construct(PagoService $_pagoService)
    {
        $this->pagoService = $_pagoService;
    }
    
    /**
     * Consulta todos los pagos registrados
     */
    public function index()
    {
        try{
            return response(PagoResource::collection(
                $this->pagoService->obtenerPagos()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar el pago'
            ], 500);
        }
    }

    /**
     * Registra el pago de un cargo a un usuario/toma,
     * sus abonos y bonificaciones.
     */
    public function store(StorePagoRequest $request)
    {
        try{
            return response(new PagoResource(
                $this->pagoService->registrarPago($request)
            ), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo procesar el pago'.$e
            ], 500);
        }
    }

    /**
     * Consulta un cargo especifico por su id
     */
    public function show($id)
    {
        try {
            return response(new PagoResource(
                $this->pagoService->busquedaPorId($id)
            ), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el pago por su id '.$id
            ], 500);
        }
    }
}
