<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cfdi;
use App\Http\Requests\StoreCfdiRequest;
use App\Http\Requests\UpdateCfdiRequest;
use App\Http\Resources\CfdiResource;
use App\Models\DatoFiscal;
use App\Models\Pago;
use App\Services\Caja\CfdiService;
use Faker\Factory as FakerFactory;
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
            $query = Cfdi::with('pagos', 'datoFiscal');

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

            if ($request->has('estatus')) {
                $query->where('estado', $request->input('estatus'));
            }

            if ($request->has('documento')) {
                $query->where('documento', 'like', '%' . $request->input('documento') . '%');
            }

            // Filtros para los campos del modelo relacionado Pago
            if ($request->has('id_caja')) {
                $query->whereHas('pagos', function ($q) use ($request) {
                    $q->where('id_caja', $request->input('id_caja'));
                });
            }

            if ($request->has('codigo')) {
                $query->whereHas('pagos', function ($q) use ($request) {
                    $id_dueno = $request->input('codigo');
                    $tipo_dueno = $request->input('modelo_dueno');

                    if ($tipo_dueno === 'usuario') {
                        $q->where('codigo_usuario', $id_dueno);
                    } elseif ($tipo_dueno === 'toma') {
                        $q->where('codigo_toma', $id_dueno);
                    }
                });
            }

            if ($request->has('forma_pago')) {
                $query->whereHas('pagos', function ($q) use ($request) {
                    $q->where('forma_pago', $request->input('forma_pago'));
                });
            }

            if ($request->has('fecha_pago')) {
                $query->whereHas('pagos', function ($q) use ($request) {
                    $q->whereDate('fecha_pago', $request->input('fecha_pago'));
                });
            }

            // Filtros para los campos de Datos Fiscales del dueño
            if ($request->has('razon_social')) {
                $query->whereHas('datoFiscal', function ($q) use ($request) {
                    $q->where('razon_social', 'like', '%' . $request->input('razon_social') . '%');
                });
            }

            if ($request->has('correo')) {
                $query->whereHas('datoFiscal', function ($q) use ($request) {
                    $q->where('correo', 'like', '%' . $request->input('correo') . '%');
                });
            }

            if ($request->has('regimen_fiscal')) {
                $query->whereHas('datoFiscal', function ($q) use ($request) {
                    $q->where('regimen_fiscal', 'like', '%' . $request->input('regimen_fiscal') . '%');
                });
            }

            if ($request->has('telefono')) {
                $query->whereHas('datoFiscal', function ($q) use ($request) {
                    $q->where('telefono', 'like', '%' . $request->input('telefono') . '%');
                });
            }

            if ($request->has('pais')) {
                $query->whereHas('datoFiscal', function ($q) use ($request) {
                    $q->where('pais', $request->input('pais'));
                });
            }

            if ($request->has('estado')) {
                $query->whereHas('datoFiscal', function ($q) use ($request) {
                    $q->where('estado', $request->input('estado'));
                });
            }

            if ($request->has('municipio')) {
                $query->whereHas('datoFiscal', function ($q) use ($request) {
                    $q->where('municipio', 'like', '%' . $request->input('municipio') . '%');
                });
            }

            if ($request->has('codigo_postal')) {
                $query->whereHas('datoFiscal', function ($q) use ($request) {
                    $q->where('codigo_postal', 'like', '%' . $request->input('codigo_postal') . '%');
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
        try {
            // Validar los datos del request
            $data = $request->validated();
            // Retornar la respuesta con el recurso creado
            return response(new CfdiResource((new CfdiService())->timbrarPago($data)), 201);
        } catch (Exception $e) {
            // Manejo de errores y retorno de una respuesta de error
            return response()->json([
                'error' => 'No se pudo solicitar el timbrado: ' . $e->getMessage()
            ], 500);
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            // Cargar el Cfdi con las relaciones pagos y datoFiscal
            $cfdi = Cfdi::with('pagos', 'datoFiscal')->findOrFail($id);

            // Retornar la respuesta con el recurso Cfdi
            return response(new CfdiResource($cfdi), 200);
        } catch (ModelNotFoundException $e) {
            // Manejar el error si el Cfdi no es encontrado
            return response()->json([
                'error' => 'No se pudo encontrar el cfdi'
            ], 404);
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
