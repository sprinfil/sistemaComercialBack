<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCajasRequest;
use App\Http\Requests\UpdateCajasRequest;
use App\Http\Resources\CajaResource;
use App\Models\Caja;
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
            $data = $request->validate();
            
            $idOperador = $data['id_operador'];
            $idCaja = $data['id_caja'];
            $caja = $data['caja'];
            $fondo = $data['fondo'];
            return $idOperador;
            DB::beginTransaction();
            $apertura = (new CajaService())->iniciarCaja($idOperador, $idCaja, $caja, $fondo);
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
    public function update(UpdateCajasRequest $request, Caja $cajas)
    {
        //
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
}
