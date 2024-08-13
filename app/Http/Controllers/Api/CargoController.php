<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCargoRequest;
use App\Http\Resources\CargoResource;
use App\Services\Caja\CargoService as CajaCargoService;
use App\Services\CargoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CargoController extends Controller
{
    protected $cargoService;

    /**
     * Constructor del controller
     */
    public function __construct(CajaCargoService $_cargoService)
    {
        $this->cargoService = $_cargoService;
    }
    
    /**
     * Consulta todos los cargos registrados
     */
    public function index()
    {
        try{
            return response(CargoResource::collection(
                $this->cargoService->obtenerCargos()
            ),200);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar el cargo'
            ], 500);
        }
    }

    /**
     * Registra el cargo de un concepto a un usuario/toma.
     */
    public function store(StoreCargoRequest $request)
    {
        try{
            return response(new CargoResource(
                $this->cargoService->generarCargo($request)
            ), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo cargar el cargo'
            ], 500);
        }
    }

    /**
     * Consulta un cargo especifico por su id
     */
    public function show($id)
    {
        try {
            return response(new CargoResource(
                $this->cargoService->busquedaPorId($id)
            ), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el cargo por su id '.$id
            ], 500);
        }
    }

    /**
     * Consulta todos los cargos de un modelo
     */
    public function cargosPorModelo(Request $request)
    {
        try {
            return response(CargoResource::collection(
                $this->cargoService->cargosPorModelo($request)
            ),200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudieron encontrar los cargos'
            ], 500);
        }
    }

    /**
     * Consulta todos los cargos de un modelo
     */
    public function cargosPorModeloPendientes(Request $request)
    {
        try {
            return response(CargoResource::collection(
                $this->cargoService->cargosPorModeloPendientes($request)
            ),200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudieron encontrar los cargos'
            ], 500);
        }
    }

    /**
     * Consulta todos los cargos pendientes de un usuario y sus tomas
     */
    public function cargosDeTomasPorUsuario(Request $request)
    {
        try {
            return response(CargoResource::collection(
                $this->cargoService->cargosPorModeloPendientes($request)
            ),200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudieron encontrar los cargos'
            ], 500);
        }
    }
}