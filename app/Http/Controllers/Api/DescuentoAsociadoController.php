<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DescuentoAsociado;
use App\Http\Requests\StoreDescuentoAsociadoRequest;
use App\Http\Requests\UpdateDescuentoAsociadoRequest;
use App\Http\Resources\DescuentoAsociadoResource;
use App\Services\AtencionUsuarios\DescuentoAsociadoService;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class DescuentoAsociadoController extends Controller
{
    protected $descuentoasociado;

    /**
     * Constructor del controller
     */
    public function __construct(DescuentoAsociado $_descuentoasociado)
    {
        $this->descuentoasociado = $_descuentoasociado;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            DB::beginTransaction();
            $descuentoasociado = (new DescuentoAsociadoService())->index();
            DB::commit();
            return $descuentoasociado;
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar los descuentos'
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreDescuentoAsociadoRequest $request)
    {
        try{
            $data = $request->validated();
            DB::beginTransaction();
            $descuento = (new DescuentoAsociadoService())->store($data);
            DB::commit();
            return $descuento;
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar el descuento'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $descuento = DescuentoAsociado::findOrFail($id);
            return response(new DescuentoAsociadoResource($descuento), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el descuento'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateDescuentoAsociadoRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $descuento = DescuentoAsociado::findOrFail($id);
            $descuento->update($data);
            $descuento->save();
            return response(new DescuentoAsociadoResource($descuento), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el descuento'
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $descuento = DescuentoAsociado::findOrFail($id);
            $descuento->delete();
            return response("Descuento eliminado con exito",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar el descuento'
            ], 500);
        }
    }
}
