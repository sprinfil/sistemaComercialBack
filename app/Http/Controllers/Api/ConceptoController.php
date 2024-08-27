<?php

namespace App\Http\Controllers\Api;

use App\Models\ConceptoCatalogo;
use App\Http\Controllers\Controller;
use App\Http\Resources\ConceptoResource;
use App\Http\Requests\StoreConceptoCatalogoRequest;
use App\Http\Requests\UpdateConceptoCatalogoRequest;
use App\Models\TarifaConceptoDetalle;
use App\Services\Caja\ConceptoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class ConceptoController extends Controller
{
    protected $conceptoService;

    /**
     * Constructor del controller
     */
    public function __construct(ConceptoService $_conceptoService)
    {
        $this->conceptoService = $_conceptoService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $this->authorize('viewAny', ConceptoCatalogo::class);
            return response(ConceptoResource::collection(
                $this->conceptoService->obtenerConceptos()
            ),200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar los conceptos'
            ], 500);
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function conceptosCargables()
    {
        try {
            $this->authorize('viewAny', ConceptoCatalogo::class);

            return response(ConceptoResource::collection(
                $this->conceptoService->obtenerConceptosCargables()
            ),200);
            
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar los conceptos'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreConceptoCatalogoRequest $request)
    {
        try {
            $this->authorize('create', ConceptoCatalogo::class);
            return response($this->conceptoService->registrarConcepto($request),200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo encontrar los conceptos'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            return response(new ConceptoResource(
                $this->conceptoService->busquedaPorId($id)
            ), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el cargo por su id '.$id
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateConceptoCatalogoRequest $request, $id)
    {
        try {
            $this->authorize('update', ConceptoCatalogo::class);
            return response($this->conceptoService->modificarConcepto($request, $id),200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo encontrar los conceptos'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try{
            $this->authorize('delete', ConceptoCatalogo::class);
            return response()->json(['message' => $this->conceptoService->eliminarConcepto($id)], 200);
        } catch (Exception $e) {
            return response()->json(['message' => 'Algo fallo'.$e], 500);
        }
    }

    public function restaurarDato(Request $request)
    {
        try {
            $this->authorize('update', ConceptoCatalogo::class);
            return response($this->conceptoService->restaurarConcepto($request),200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo encontrar los conceptos'
            ], 500);
        }
    }
}