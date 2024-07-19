<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCajasRequest;
use App\Http\Requests\UpdateCajasRequest;
use App\Http\Resources\CajaResource;
use App\Models\Caja;
use Illuminate\Database\Eloquent\ModelNotFoundException;

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
        //
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
}
