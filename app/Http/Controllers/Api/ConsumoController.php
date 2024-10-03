<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consumo;
use App\Http\Requests\StoreConsumoRequest;
use App\Http\Requests\UpdateConsumoRequest;
use App\Http\Resources\ConsumoResource;
use App\Services\Facturacion\ConsumoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ConsumoController extends Controller
{
    protected $consumoService;

     /**
     * Constructor del controller
     */
    public function __construct(ConsumoService $_consumoService)
    {
        $this->consumoService = $_consumoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $data = $request->all();
            return response(ConsumoResource::collection(
                $this->consumoService->buscarConsumos($data)
            ), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los consumos: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConsumoRequest $request)
    {
        try {
            $data = $request->all();
            return response(new ConsumoResource(
                $this->consumoService->registrarConsumo($data)
            ), 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo procesar el consumo: '.$e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response(new ConsumoResource(
                $this->consumoService->busquedaPorId($id)
            ), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el consumo por su id ' . $id
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConsumoRequest $request, Consumo $consumo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Consumo $consumo)
    {
        //
    }
}
