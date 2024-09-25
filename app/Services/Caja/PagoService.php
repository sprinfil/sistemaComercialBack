<?php

namespace App\Services\Caja;

use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Http\Resources\PagoResource;
use App\Models\Abono;
use App\Models\Caja;
use App\Models\Cargo;
use App\Models\CatalogoBonificacion;
use App\Models\Pago;
use App\Models\Toma;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;

class PagoService
{

    // metodo para obtener todos los cargos registrados
    public function obtenerPagos(): Collection
    {
        try {
            return Pago::all();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // total de pagos apagar
    public function totalPagos(array $cargos)
    {
        $total_a_pagar = 0;
        foreach ($cargos as $cargo) {
            $cargo_selecionado = Cargo::findOrFail($cargo['id_cargo']); // Asumiendo que cargo es un array
            $total_a_pagar += $cargo_selecionado->montoPendiente(); // Asumiendo que las propiedades existen
        }
        return $total_a_pagar;
    }

    // método para cargar un cargo a un usuario
    public function registrarPago(StorePagoRequest $request)
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();
            // se obtiene el dueño de los cargos
            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            $dueno = helperGetOwner($modelo, $id_modelo);
            // se obtiene la caja y se registra el pago
            $caja = Caja::findOrFail($data['id_caja']);
            $numeroPagos = $caja->pagos()->count() ?? 0 + 1;
            $folio = strtoupper('C' . str_pad($caja->id, 2, '0', STR_PAD_LEFT) . 'P' . str_pad($numeroPagos, 4, '0', STR_PAD_LEFT));
            $data['folio'] = $folio;
            $data['fecha_pago'] = date('Y-m-d');
            $pago = Pago::create($data);
            // se obtiene el saldo
            $saldo_inicial = $dueno->saldoPendiente();

            // se obtiene el monto pagado
            $monto_pagado =  number_format($pago->total_pagado, 2, '.', '');
            // se obtienen los cargos a pagar (si hay) y cuanto se va pagar
            if (isset($data['cargos']) && !empty($data['cargos'])) {
                $cargos_selecionados = $data['cargos'];
                // Proceder con la lógica cuando cargos existe y no está vacío
                $total_a_pagar = number_format($this->totalPagos($cargos_selecionados), 2, '.', '');
            } else {
                $cargos_selecionados = null;
                $total_a_pagar = 0;
            }

            $abono_acumulado = 0;

            // valida si hay cargos selecionados
            if ($cargos_selecionados) {
                // se calcula la diferencia entre el total de los cargos a pagar y el pago
                //$diferencia = abs($monto_pagado - $total_a_pagar);
                if ($total_a_pagar <= $monto_pagado) {
                    // si la diferencia de lo pagado es menor o la diferencia es poca se hacen los cargos
                    foreach ($cargos_selecionados as $cargo) {
                        $cargo_selecionado = Cargo::findOrFail($cargo['id_cargo']);
                        $abono_acumulado += number_format($cargo_selecionado->montoPendiente(), 2, '.', '');
                        $this->registrarAbono($cargo['id_cargo'], 'pago', $pago->id, $cargo_selecionado->montoPendiente());
                        $this->consolidarEstados($id_modelo, $modelo);
                    }
                    // si lo pagado no consume todo lo pagado
                    // -> llama la funcion de pago y se aplica el saldo a favor
                    if ($abono_acumulado < $monto_pagado) {
                        //throw new Exception("Lo pagado es menor que el adeudo total ".$abono_acumulado ."<". $monto_pagado);
                        $this->pagoAutomatico($id_modelo, $modelo);
                    }
                } else {
                    // si no hay cargos a pagar
                    // -> llama la funcion de pago y se aplica el saldo a favor
                    $this->pagoAutomatico($id_modelo, $modelo);
                    //throw new Exception("Lo pagado es menor que el adeudo total ".$total_a_pagar ."<". $monto_pagado);
                }
            } else {
                $this->pagoAutomatico($id_modelo, $modelo);
            }

            $this->consolidarEstados($id_modelo, $modelo);

            $pago_final = Pago::with('abonos')->findOrFail($pago->id);
            $pago_final->saldo_anterior = number_format($saldo_inicial, 2, '.', '');
            $pago_final->saldo_pendiente = number_format($dueno->saldoPendiente(), 2, '.', '');
            $pago_final->saldo_a_favor = number_format($dueno->saldoSinAplicar(), 2, '.', '');
            $pago_final->total_abonado = number_format($pago_final->total_abonado(), 2, '.', '');
            $pago_final->save();

            $datos_fiscales = $dueno->datos_fiscales;
            if ($datos_fiscales) {
                $cfdi_data = [];
                $cfdi_data['folio'] = $pago_final->folio;
                $cfdi_data['id_timbro'] = $caja->id_operador;
                $cfdi_data['metodo'] = 'directo';

                $estado_timbrado = (new CfdiService())->timbrarPago($cfdi_data); // TODO que deberia pasar si un timbrado falla?
            }

            DB::commit();

            //throw new Exception("L");
            return $pago_final;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function pagoAutomatico($_id_modelo, $_modelo)
    {
        try {
            $dueno = helperGetOwner($_modelo, $_id_modelo);
            $pagos_pendientes = $dueno->pagosPendientes;
            $cargos_vigentes = $dueno->cargosVigentesConConcepto;

            if ($pagos_pendientes) {
                foreach ($pagos_pendientes as $pago) {
                    $total_por_prioridad = 0;

                    if ($cargos_vigentes) {
                        $cargos_ordenados = $this->clasificarPorPrioridad($cargos_vigentes);
                        foreach ($cargos_ordenados as $prioridad => $grupo) {
                            $total_por_prioridad = number_format($grupo['total'], 2, '.', '');
                            //$cantidad_de_cargos = count($grupo['cargos']);

                            foreach ($grupo['cargos'] as $cargo) {
                                $pago_real = Pago::find($pago->id);
                                $total_pendiente = $pago_real->pendiente();
                                if ($total_pendiente > 0) {
                                    $cargo_selecionado = Cargo::findOrFail($cargo['id']);
                                    $monto_con_iva = number_format($cargo_selecionado->montoPendiente(), 2, '.', '');
                                    $pago_porcentual = number_format((($monto_con_iva) * 100) / $total_por_prioridad, 2, '.', '');
                                    $abono_final = number_format($pago_porcentual * $total_pendiente / 100, 2, '.', '');
                                    if ($abono_final >= $monto_con_iva) {
                                        $this->registrarAbono($cargo['id'], 'pago', $pago->id, $monto_con_iva);
                                        $this->consolidarEstados($_id_modelo, $_modelo);
                                    } else if ($abono_final < $monto_con_iva) {
                                        // Validar si el cargo es abonable
                                        if ($cargo_selecionado->concepto->abonable) {
                                            if ($cargo_selecionado->concepto->prioridad_por_antiguedad) {
                                                // Primero verifica si el pago pendiente puede saldar el cargo completo
                                                if ($total_pendiente > $monto_con_iva) {
                                                    $this->registrarAbono($cargo['id'], 'pago', $pago->id, $monto_con_iva);
                                                    $this->consolidarEstados($_id_modelo, $_modelo);
                                                    // No hacemos break aquí, ya que queremos continuar abonando los siguientes cargos si es posible
                                                } else if ($total_pendiente == $monto_con_iva) {
                                                    $this->registrarAbono($cargo['id'], 'pago', $pago->id, $monto_con_iva);
                                                    $this->consolidarEstados($_id_modelo, $_modelo);
                                                    break 2;
                                                    // No hacemos break aquí, ya que queremos continuar abonando los siguientes cargos si es posible
                                                } else if ($total_pendiente > 0) {
                                                    // Si no puede saldar el cargo, abonar lo que se pueda del total pendiente
                                                    $this->registrarAbono($cargo['id'], 'pago', $pago->id, $total_pendiente);
                                                    $this->consolidarEstados($_id_modelo, $_modelo);
                                                    // Aquí se hace break para evitar continuar si el pendiente se ha agotado
                                                    break 2;
                                                } else {
                                                    break 3;
                                                }
                                            } else {
                                                $this->registrarAbono($cargo['id'], 'pago', $pago->id, $abono_final);
                                                $this->consolidarEstados($_id_modelo, $_modelo);
                                            }
                                        } else {
                                            // El cargo no es abonable, puedes manejar este caso.
                                            break 2;
                                        }
                                    }
                                } else {
                                    break 2;
                                }
                            }
                        }
                    } else {
                        break;
                    }
                }
            } else {
                // no hay pagos
            }

            //return $cargos_ordenados; // Convertir la colección a JSON
        } catch (Exception $ex) {
            throw new Exception("Error al procesar pago: " . $ex->getMessage());
        }
    }

    function clasificarPorPrioridad(EloquentCollection $cargos): array
    {
        $clasificado = collect();

        // Agrupar los cargos por prioridad
        $cargos->groupBy(function ($cargo) {
            return (string)$cargo->concepto->prioridad_abono;
        })->each(function ($cargosConMismaPrioridad, $prioridad) use ($clasificado) {

            // Ordenar los cargos dentro de cada prioridad
            $cargosOrdenados = $cargosConMismaPrioridad->sort(function ($a, $b) {
                // Ordenar por abonable (abonable = 1 primero)
                if ($a->concepto->abonable !== $b->concepto->abonable) {
                    return $a->concepto->abonable ? -1 : 1;
                }

                // Si ambos tienen la misma prioridad por antigüedad, ordenar por fecha
                if ($a->concepto->prioridad_por_antiguedad && $b->concepto->prioridad_por_antiguedad) {
                    return strtotime($a->fecha_cargo) - strtotime($b->fecha_cargo);
                }

                // Mantener el orden original si no hay criterios de ordenación adicionales
                return 0;
            });

            // Calcular el total de monto e IVA
            $total = $cargosOrdenados->sum(function ($cargo) {
                return $cargo->monto + $cargo->iva;
            });

            // Añadir los cargos ordenados y el total a la colección clasificada
            $clasificado->put($prioridad, [
                'cargos' => $cargosOrdenados->values()->toArray(),  // Guardamos la colección ordenada
                'total' => $total,
            ]);
        });

        // Ordenar el clasificado por prioridad ascendente para que se mantenga el orden correcto
        $clasificado = $clasificado->sortKeys();

        return $clasificado->toArray();
    }

    //
    public function registrarAbono($id_cargo, $modelo_origen, $id_origen, $monto)
    {
        try {
            $nuevo_abono_data = [];
            $nuevo_abono_data['id_cargo'] = $id_cargo;
            $nuevo_abono_data['id_origen'] = $id_origen;
            $nuevo_abono_data['modelo_origen'] = $modelo_origen;
            $nuevo_abono_data['total_abonado'] = $monto;

            return Abono::create($nuevo_abono_data);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    //
    public function procesarPagos($_id_modelo, $_modelo)
    {
        DB::beginTransaction();
    }

    // metodo para consolidar estados de cargos, pagos y abonos
    public function consolidarEstados($_id_modelo, $_modelo)
    {
        try {
            DB::beginTransaction();
            // tipo pago
            $modelo = $_modelo;
            $id_modelo = $_id_modelo;
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if ($modelo && $id_modelo) {
                if ($modelo == 'usuario') {
                    $dueno = Usuario::findOrFail($id_modelo);
                } else if ($modelo == 'toma') {
                    $dueno = Toma::findOrFail($id_modelo);
                } else {
                    throw new Exception('modelo definido incorrectamente');
                }
                // consolidar estados pagos y cargos
                $estado_pagos = $this->consolidarEstadosDePago($dueno);
                $estado_cargos = $this->consolidarEstadosDeCargo($dueno);
                if ($estado_pagos == null && $estado_cargos == null) {
                    DB::commit();
                } else {
                    $error = " ";
                    if ($estado_pagos) {
                        $error = $error . " " . $estado_pagos;
                    }
                    if ($estado_cargos) {
                        $error = $error . " " . $estado_cargos;
                    }
                    throw new Exception('error en la consolidacion de estados: ' . $error);
                }
            } else {
                throw new Exception('modelo no definido');
            }
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }

    public function consolidarEstadosDePago($dueno)
    {
        try {
            // carga todos los pagos pendientes
            $pagos = $dueno->pagosPendientes;
            if ($pagos) {
                // se recorren todos los pagos pendientes
                foreach ($pagos as $pago) {
                    // cada pago puede tener abonos
                    $total_abonado = 0;
                    $total_pagado = $pago->total_pagado;
                    $abonos_aplicados = $pago->abonosVigentes;

                    // se recorren los abonos realizados para saber
                    // si queda saldo por aplicar de los pagos
                    if ($abonos_aplicados) {
                        foreach ($abonos_aplicados as $abono) {
                            $total_abonado += $abono->total_abonado;
                        }
                        // después de recorrer todos los abonos
                        $diferencia = abs($total_abonado - $total_pagado);
                        if ($diferencia < 1 || $total_abonado == $total_pagado) {
                            // si la diferencia es menor a 1
                            $pago_modificado = Pago::findOrFail($pago->id);
                            $pago_modificado->update([
                                'estado' => 'abonado'
                            ]);
                            $pago_modificado->save();
                        } else if ($total_abonado < $total_pagado) {
                            //throw new Exception('menor abono'.$total_abonado.'pago'.$total_pagado);
                            // si la suma de abonos es menor al total pagado
                            $pago_modificado = Pago::findOrFail($pago->id);
                            $pago_modificado->update([
                                'estado' => 'pendiente'
                            ]);
                            $pago_modificado->save();
                        } else {
                            throw new Exception('error abono ' . $total_abonado . ' > pago ' . $total_pagado . ' ' . $abonos_aplicados);
                        }
                    } else {
                        throw new Exception('no hay abonos');
                    }
                }
            } else {
                throw new Exception('no hay pagos');
            }
            return null;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function consolidarEstadosDeCargo($dueno)
    {
        try {
            // carga todos cargos pendientes
            $cargos = $dueno->cargosVigentes;
            if ($cargos) {
                // se recorren todos los pagos pendientes
                foreach ($cargos as $cargo) {
                    // cada cargo puede tener abonos
                    $total_abonado_al_cargo = 0;
                    $total_cargo = $cargo->monto + $cargo->iva;
                    $abonos_al_cargo = $cargo->abonosVigentes;
                    if ($abonos_al_cargo) {
                        foreach ($abonos_al_cargo as $abono) {
                            $total_abonado_al_cargo += $abono->total_abonado;
                        }
                        // después de recorrer todos los abonos
                        $diferencia = abs($total_abonado_al_cargo - $total_cargo);
                        if ($diferencia < 1) {
                            // si la diferencia es menor a 1
                            $cargo_modificado = Cargo::findOrFail($cargo->id);
                            $cargo_modificado->update([
                                'estado' => 'pagado'
                            ]);
                            $cargo_modificado->save();
                        } else if ($total_abonado_al_cargo < $total_cargo) {
                            // si la suma de abonos es menor al total del cargo
                            $cargo_modificado = Cargo::findOrFail($cargo->id);
                            $cargo_modificado->update([
                                'estado' => 'pendiente'
                            ]);
                            $cargo_modificado->save();
                        }
                    } else {
                        throw new Exception('no hay abonos');
                    }
                }
            } else {
                throw new Exception('no hay cargos');
            }
            return null;
        } catch (Exception $ex) {
            return $ex;
        }
    }

    public function cancelarPagoYConsolidarCargos($origen, $id_origen)
    {
        try {
            $abonos = Abono::where('modelo_origen', $origen)
                ->where('id_origen', $id_origen)
                ->get();

            if ($abonos->isEmpty()) {
                throw new Exception('No hay abonos relacionados con este pago.');
            }

            // Recorre cada abono y obtiene el cargo asociado
            foreach ($abonos as $abono) {
                $cargo = $abono->cargo; // Asumiendo que tienes una relación abono->cargo

                if ($cargo) {
                    // Consolida los estados de los cargos
                    $total_abonado_al_cargo = 0;
                    $total_cargo = $cargo->monto + $cargo->iva;

                    // Obtener todos los abonos de ese cargo
                    $abonos_del_cargo = $cargo->abonosVigentes;

                    // Suma todos los abonos aplicados a ese cargo
                    foreach ($abonos_del_cargo as $abono_cargo) {
                        $total_abonado_al_cargo += $abono_cargo->total_abonado;
                    }

                    // Calcula la diferencia entre el total abonado y el total del cargo
                    $diferencia = abs($total_abonado_al_cargo - $total_cargo);

                    if ($diferencia < 1) {
                        // Si la diferencia es menor a 1, el cargo está pagado
                        $cargo->update(['estado' => 'pagado']);
                    } elseif ($total_abonado_al_cargo < $total_cargo) {
                        // Si el total abonado es menor al cargo, el estado es "pendiente"
                        $cargo->update(['estado' => 'pendiente']);
                    } else {
                        // Si es mayor (aunque esto no debería suceder), podrías lanzar una excepción o manejar el caso.
                        throw new Exception('Inconsistencia en los abonos del cargo.');
                    }
                } else {
                    throw new Exception('El abono no tiene un cargo asociado.');
                }
            }

            return 'Pago cancelado y cargos consolidados correctamente.';
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function generarAbonos() {}

    // metodo para buscar un pago por su id
    public function busquedaPorId($id): Pago
    {
        try {
            return Pago::findOrFail($id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // metodo para buscar un pago por su id
    public function busquedaPorFolio($folio): Pago
    {
        try {
            return Pago::where('folio', $folio)->firstOrFail();
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // metodo para buscar todos los pagos de un modelo especifico
    public function pagosPorModelo(Request $request)
    {
        try {
            $data = $request->all();

            // tipo pago
            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if ($modelo == 'usuario') {
                $dueno = Usuario::findOrFail($id_modelo);
                $pagos = $dueno->pagos;
                if ($pagos) {
                    return $pagos;
                } else {
                    throw new Exception('el modelo no contiene pagos');
                }
            } else if ($modelo == 'toma') {
                $dueno = Toma::findOrFail($id_modelo);
                $pagos = $dueno->pagos;
                if ($pagos) {
                    return $pagos;
                } else {
                    throw new Exception('el modelo no contiene pagos');
                }
            } else {
                throw new Exception('modelo no definido');
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // metodo para buscar todos los pagos de un modelo especifico
    public function pagosPorModeloPendiente(Request $request)
    {
        try {
            $data = $request->all();

            // tipo pago
            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if ($modelo == 'usuario') {
                $dueno = Usuario::findOrFail($id_modelo);
                $pagos = $dueno->pagosPendientes;
                if ($pagos) {
                    return $pagos;
                } else {
                    throw new Exception('el modelo no contiene pagos');
                }
            } else if ($modelo == 'toma') {
                $dueno = Toma::findOrFail($id_modelo);
                $pagos = $dueno->pagosPendientes;
                if ($pagos) {
                    return $pagos;
                } else {
                    throw new Exception('el modelo no contiene pagos');
                }
            } else {
                throw new Exception('modelo no definido');
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // metodo para buscar todos los pagos de un modelo especifico
    public function pagosPorModeloConDetalle(Request $request)
    {
        try {
            $data = $request->all();

            // tipo pago
            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if ($modelo == 'usuario') {
                $dueno = Usuario::findOrFail($id_modelo);
                $pagos = $dueno->pagosConDetalle;
                if ($pagos) {
                    return $pagos;
                } else {
                    throw new Exception('el modelo no contiene pagos');
                }
            } else if ($modelo == 'toma') {
                $dueno = Toma::findOrFail($id_modelo);
                $pagos = $dueno->pagosConDetalle;
                if ($pagos) {
                    return $pagos;
                } else {
                    throw new Exception('el modelo no contiene pagos');
                }
            } else {
                throw new Exception('modelo no definido');
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // actualizar pago
    public function modificarPago(UpdatePagoRequest $request, $id): Pago
    {
        try {
            $data = $request->validated();
            $pago = Pago::findOrFail($id);
            $pago->update($data);
            $pago->save();
            return $pago;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // eliminar pago
    public function eliminarPago($id)
    {
        try {
            $pago = Pago::findOrFail($id);
            $pago->delete();
            return "Pago eliminado con exito";
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    // aplicación del pago sobre abono en los cargos
    public function totalPendiente(Request $request)
    {
        try {
            $pagos = $this->pagosPorModeloPendiente($request);
            $total_pendiente = 0;
            foreach ($pagos as $pago) {
                $total_pendiente += $pago->pendiente();
            }
            return $total_pendiente;
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}
