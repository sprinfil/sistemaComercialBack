<?php

namespace App\Services\AtencionUsuarios;

use App\Models\Ajuste;
use App\Models\AjusteCatalogo;
use App\Models\Cargo;
use App\Services\Caja\PagoService;
use Exception;
use Illuminate\Support\Facades\DB;

class AjusteService
{
    public function consultarAjustes()
    {
        try {
            return Ajuste::all();
        } catch (Exception $e) {
            return $e;
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
}
