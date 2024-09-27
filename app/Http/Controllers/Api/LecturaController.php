<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLecturaRequest;
use App\Http\Requests\StorePagoRequest;
use App\Http\Resources\LecturaResource;
use App\Http\Resources\PagoResource;
use App\Services\Facturacion\LecturaService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LecturaController extends Controller
{
    protected $lecturaService;

    /**
     * Constructor del controller
     */
    public function __construct(LecturaService $_lecturaService)
    {
        $this->lecturaService = $_lecturaService;
    }

    /**
     * Consulta todos los pagos registrados
     */
    public function index(Request $request)
    {
        try {
            $data = $request->all();
            return response(LecturaResource::collection(
                $this->lecturaService->buscarLecturas($data)
            ), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar la lectura: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Registra el pago de un cargo a un usuario/toma,
     * sus abonos y bonificaciones.
     */
    public function store(StoreLecturaRequest $request)
    {
        try {
            $data = $request->all();
            return response(new LecturaResource(
                $this->lecturaService->registrarLectura($data)
            ), 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo procesar la lectura: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Consulta un cargo especifico por su id
     */
    public function show($id)
    {
        try {
            return response(new LecturaResource(
                $this->lecturaService->busquedaPorId($id)
            ), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la lectura por su id ' . $id
            ], 500);
        }
    }

    public function import(Request $request)
    {

        try {
            DB::beginTransaction();
            $data = $request->all()['lecturas'];
            $lecturas = (new LecturaService())->importarLecturas($data);
            DB::commit();
            return response()->json(['lecturas' => LecturaResource::collection($lecturas)], 200);
        } catch (Exception $ex) {
            return response()->json(['error' => 'No se pudo crear el precontrato para las tomas'], 500);
        }
    }
}