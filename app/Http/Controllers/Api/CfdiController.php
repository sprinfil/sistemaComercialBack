<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cfdi;
use App\Http\Requests\StoreCfdiRequest;
use App\Http\Requests\UpdateCfdiRequest;
use App\Http\Resources\CfdiResource;
use App\Models\Pago;
use Exception;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class CfdiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            // Comienza la consulta base de Cfdi con la relación Pago
            $query = Cfdi::with('pagos');

            // Filtros para los campos de Cfdi
            if ($request->has('folio')) {
                $query->where('folio', $request->input('folio'));
            }

            if ($request->has('id_timbro')) {
                $query->where('id_timbro', $request->input('id_timbro'));
            }

            if ($request->has('metodo')) {
                $query->where('metodo', $request->input('metodo'));
            }

            if ($request->has('estado')) {
                $query->where('estado', $request->input('estado'));
            }

            if ($request->has('documento')) {
                $query->where('documento', 'like', '%' . $request->input('documento') . '%');
            }

            // Filtros para los campos del modelo relacionado Pago
            if ($request->has('id_caja')) {
                $query->whereHas('pago', function ($q) use ($request) {
                    $q->where('id_caja', $request->input('id_caja'));
                });
            }

            if ($request->has('id_dueno')) {
                $query->whereHas('pago', function ($q) use ($request) {
                    $q->where('id_dueno', $request->input('id_dueno'));
                });
            }

            if ($request->has('total_pagado')) {
                $query->whereHas('pago', function ($q) use ($request) {
                    $q->where('total_pagado', $request->input('total_pagado'));
                });
            }

            if ($request->has('saldo_pendiente')) {
                $query->whereHas('pago', function ($q) use ($request) {
                    $q->where('saldo_pendiente', $request->input('saldo_pendiente'));
                });
            }

            if ($request->has('forma_pago')) {
                $query->whereHas('pago', function ($q) use ($request) {
                    $q->where('forma_pago', $request->input('forma_pago'));
                });
            }

            if ($request->has('fecha_pago')) {
                $query->whereHas('pago', function ($q) use ($request) {
                    $q->whereDate('fecha_pago', $request->input('fecha_pago'));
                });
            }

            // Ejecutar la consulta
            $cfdis = $query->get();

            // Retornar los resultados con el resource CfdiResource
            return CfdiResource::collection($cfdis);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrió un error durante la búsqueda de los CFDIs: ' . $ex->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCfdiRequest $request)
    {
        try{
            $data = $request->validated();
            $data['estado'] = 'pendiente';

            $cfdi = Cfdi::create($data);
            return response(new CfdiResource($cfdi), 201);
        } catch(Exception $e) {
            return response()->json([
                'error' => 'No se pudo solicitar el timbrado'.$e
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $cfdi = Cfdi::findOrFail($id);
            return response(new CfdiResource($cfdi), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar el cfdi'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCfdiRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $cfdi = Cfdi::findOrFail($id);
            $cfdi->update($data);
            $cfdi->save();
            return response(new CfdiResource($cfdi), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar el cfdi'
            ], 500);
        }
    }
}
