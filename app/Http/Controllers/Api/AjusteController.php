<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CompareAjusteRequest;
use App\Models\Ajuste;
use App\Http\Requests\StoreAjusteRequest;
use App\Http\Requests\UpdateAjusteRequest;
use App\Http\Resources\AjusteResource;
use App\Services\AtencionUsuarios\AjusteService;
use Exception;

class AjusteController extends Controller
{
    protected $ajusteService;

    /**
     * Constructor del controller
     */
    public function __construct(AjusteService $_ajusteService)
    {
        $this->ajusteService = $_ajusteService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response(AjusteResource::collection(
                $this->ajusteService->consultarAjustes()
            ), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los ajustes'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function compare(CompareAjusteRequest $request)
    {
        try {
            $data = $request->all();
            return response(
                $this->ajusteService->conceptosAjustables($data),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los ajustes'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAjusteRequest $request)
    {
        try {
            $data = $request->all();
            return response(
                new AjusteResource($this->ajusteService->crearAjuste($data)),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible ajustar los cargos'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response(
                $this->ajusteService->consultarAjuste($id),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los ajustes'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function cancel(UpdateAjusteRequest $request)
    {
        try {
            $data = $request->all();
            return response(
                new AjusteResource($this->ajusteService->cancelarAjuste($data)),
                200
            );
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible cancelar el ajuste'
            ], 500);
        }
    }
}
