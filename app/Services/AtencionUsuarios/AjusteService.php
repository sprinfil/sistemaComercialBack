<?php

namespace App\Services\AtencionUsuarios;

use App\Models\Ajuste;
use App\Models\AjusteCatalogo;
use App\Models\Cargo;
use App\Models\Toma;
use App\Services\Caja\PagoService;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AjusteService
{
    public function consultarAjustes(Request $request)
    {
        try {
            // Inicia la consulta base
            $query = Ajuste::query();

            // Filtra por el catálogo de ajustes
            if ($request->has('id_ajuste_catalogo')) {
                $query->where('id_ajuste_catalogo', $request->input('id_ajuste_catalogo'));
            }

            // Filtra por el dueño del ajuste (usuario o toma)
            if ($request->has('id_modelo_dueno') && $request->has('modelo_dueno')) {
                $query->where('id_modelo_dueno', $request->input('id_modelo_dueno'))
                    ->where('modelo_dueno', $request->input('modelo_dueno'));
            }

            // Filtra por operador
            if ($request->has('id_operador')) {
                $query->where('id_operador', $request->input('id_operador'));
            }

            // Filtra por monto ajustado
            if ($request->has('monto_ajustado')) {
                $query->where('monto_ajustado', $request->input('monto_ajustado'));
            }

            // Filtra por estado
            if ($request->has('estado')) {
                $query->where('estado', $request->input('estado'));
            }

            // Filtra por comentario
            if ($request->has('comentario')) {
                $query->where('comentario', 'like', '%' . $request->input('comentario') . '%');
            }

            // Filtra por motivo de cancelación
            if ($request->has('motivo_cancelacion')) {
                $query->where('motivo_cancelacion', 'like', '%' . $request->input('motivo_cancelacion') . '%');
            }

            // Ejecuta la consulta y obtiene los resultados
            $ajustes = $query->get();

            return $ajustes;
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Ocurrió un error al consultar los ajustes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function consultarAjuste($id)
    {
        try {
            return Ajuste::findOrFail($id);
        } catch (Exception $e) {
            return $e;
        }
    }
    public function consultarAjustePorCodigo($data)
    {
        try {
            return Ajuste::where("id_modelo_dueno", $data['id'])
                ->where("modelo", $data['modelo'])
                ->get();
        } catch (Exception $e) {
            return $e->getMessage(); // Retornar el mensaje del error en lugar del objeto Exception
        }
    }
    public function conceptosAjustables($data)
    {
        try {
            $ajuste = AjusteCatalogo::findOrFail($data['id_ajuste_catalogo']);
            $conceptos_aplicables = $ajuste->conceptosAplicables;
            $dueno = helperGetOwner($data['modelo_dueno'], $data['id_modelo_dueno']);
            $cargos_pendientes = null;
            $cargos_ajustables = [];

            if ($dueno) {
                $cargos_pendientes = $dueno->cargosVigentes;

                if ($cargos_pendientes) {
                    foreach ($cargos_pendientes as $cargo) {
                        foreach ($conceptos_aplicables as $concepto) {
                            if ($concepto->id_concepto_catalogo == $cargo->id_concepto) {
                                // Añadir registro a arreglo de cargo->id y concepto->porcentaje_ajustable
                                $cargos_ajustables[] = [
                                    'cargo_id' => $cargo->id,
                                    'cargo' => $cargo->nombre,
                                    'monto_pendiente' => $cargo->montoPendiente(),
                                    'concepto_id' => $concepto->id,
                                    'concepto' => $cargo->concepto->nombre,
                                    'porcentaje_ajustable' => $concepto->rango_maximo
                                ];
                            }
                        }
                    }
                } else {
                    throw new Exception("No se encontraron cargos vigentes");
                }
            } else {
                throw new Exception("No se encontró el dueño");
            }
            return $cargos_ajustables; // Retornar el arreglo de cargos ajustables
        } catch (Exception $e) {
            return $e;
        }
    }
    public function crearAjuste($data)
    {
        try {
            DB::beginTransaction();
            $dueno = helperGetOwner($data['modelo_dueno'], $data['id_modelo_dueno']);

            $cargos_ajustados = $data['cargos_ajustados'];
            $monto_bonificado = 0;
            $monto_total = 0;

            // Crear ajuste inicial con montos en 0
            $nuevo_ajuste = Ajuste::create([
                'id_ajuste_catalogo' => $data['id_ajuste_catalogo'],
                'id_modelo_dueno' => $data['id_modelo_dueno'],
                'modelo_dueno' => $data['modelo_dueno'],
                'id_operador' => $data['id_operador'],
                'monto_ajustado' => $monto_bonificado,
                'monto_total' => $monto_total,
                'estado' => 'activo',
                'comentario' => $data['comentario'] ?? null
            ]);

            if ($dueno) {
                if ($cargos_ajustados) {
                    foreach ($cargos_ajustados as $cargo) {
                        // Buscar el cargo y registrar bonificación
                        $cargo_seleccionado = Cargo::findOrFail($cargo['id_cargo']);
                        $monto_pendiente = $cargo_seleccionado->montoPendiente();

                        $estatus = (new PagoService())->registrarAbono($cargo['id_cargo'], 'ajuste', $nuevo_ajuste->id, $cargo['monto_bonificado']);

                        if (!$estatus) {
                            throw new Exception("Error al registrar el abono para el cargo ID: " . $cargo['id_cargo']);
                        }

                        // Sumar montos
                        $monto_bonificado += $cargo['monto_bonificado'];
                        $monto_total += $monto_pendiente;
                    }

                    (new PagoService())->consolidarEstados($data['id_modelo_dueno'], $data['modelo_dueno']);

                    // Actualizar montos en el ajuste
                    $nuevo_ajuste->update([
                        'monto_ajustado' => $monto_bonificado,
                        'monto_total' => $monto_total
                    ]);
                } else {
                    throw new Exception("No se encontraron cargos ajustados");
                }
            } else {
                throw new Exception("No se encontró el dueño");
            }

            DB::commit();
            return $nuevo_ajuste;
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
    public function cancelarAjuste($data)
    {
        try {
            DB::beginTransaction();
            $ajuste = Ajuste::findOrFail($data["id"]);
            $ajuste->update([
                'estado' => 'cancelado',
                'motivo_cancelacion' => $data['motivo_cancelacion'] ?? null
            ]);
            //public function cancelarPagoYConsolidarCargos($origen, $id_origen)
            $estatus = (new PagoService())->cancelarPagoYConsolidarCargos('ajuste', $ajuste->id); //->consolidarEstados($ajuste->id_modelo_dueno, $ajuste->modelo_dueno);
            if (!$estatus) {
                throw new Exception('qpd');
            }
            DB::commit();
            return $ajuste;
        } catch (Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
    public function generarReportes($filtros)
    {
        //$ReporteAjustes=Ajuste::get()->toArray();
        $ReporteAjustes = Ajuste::with('ajusteCatalogo', 'dueno', 'operador')->get()->map(function ($ajuste) {
            // Process the 'name' attribute and add it as a new field
            $ajuste->created_at = Carbon::parse($ajuste->created_at, "GMT-7")->format("Y-m-d");
            return $ajuste;
        });
        return $ReporteAjustes;
    }
}
