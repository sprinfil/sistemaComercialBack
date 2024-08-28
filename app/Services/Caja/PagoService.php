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

class PagoService{

    // metodo para obtener todos los cargos registrados
    public function obtenerPagos(): Collection
    {
        try{
            return Pago::all();
        } catch(Exception $ex){
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
            $caja = Caja::find($data['id_caja']);
            $numeroPagos = $caja->pagos()->count() + 1;
            $folio = strtoupper('C'.str_pad($caja->id, 2, '0', STR_PAD_LEFT).'P' . str_pad($numeroPagos, 4, '0', STR_PAD_LEFT));
            $data['folio'] = $folio;
            $pago = Pago::create($data);
            // se obtiene el saldo
            $saldo_inicial = $dueno->saldoToma();

            // se obtiene el monto pagado
            $monto_pagado =  number_format($pago->total_pagado, 2, '.', '');
            // se obtienen los cargos a pagar (si hay) y cuanto se va pagar
            $cargos_selecionados = $data['cargos'];
            $total_a_pagar = number_format($this->totalPagos($cargos_selecionados), 2, '.', '');

            // valida si hay cargos selecionados
            if($cargos_selecionados){
                // se calcula la diferencia entre el total de los cargos a pagar y el pago
                //$diferencia = abs($monto_pagado - $total_a_pagar);
                if($total_a_pagar <= $monto_pagado){
                    // si la diferencia de lo pagado es menor o la diferencia es poca se hacen los cargos
                    foreach ($cargos_selecionados as $cargo) {
                        $cargo_selecionado = Cargo::findOrFail($cargo['id_cargo']);
                        $this->registrarAbono($cargo['id_cargo'], 'pago', $pago->id, $cargo_selecionado->montoPendiente());
                        $this->consolidarEstados($id_modelo, $modelo);
                    }
                    // si lo pagado no consume todo lo pagado
                    // -> llama la funcion de pago y se aplica el saldo a favor
                } else {
                    // si no hay cargos a pagar
                    // -> llama la funcion de pago y se aplica el saldo a favor
                    throw new Exception("Lo pagado es menor que el adeudo total ".$total_a_pagar ."<". $monto_pagado);
                } 
            } else {
                throw new Exception("No hay cargos a pagar");
            }

            $this->consolidarEstados($id_modelo, $modelo);
            DB::commit();
            $pago_final = Pago::with('abonos')->findOrFail($pago->id);
            $pago_final->saldo_anterior = number_format($saldo_inicial, 2, '.', '');
            $pago_final->saldo_actual = number_format($dueno->saldoToma(), 2, '.', '');
            $pago_final->saldo_no_aplicado = number_format($dueno->saldoSinAplicar(), 2, '.', '');
            return $pago_final;
        } 
        catch(Exception $ex){
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

            if ($cargos_vigentes) {
                $cargos_ordenados = $this->clasificarPorPrioridad($cargos_vigentes);
                
            } else {
                throw new Exception("No hay cargos por pagar");
            }
            
            return $cargos_ordenados; // Convertir la colección a JSON
        } 
        catch (Exception $ex) {
            throw new Exception("Error al procesar pago: " . $ex->getMessage());
        }
    }
    
    function clasificarPorPrioridad(EloquentCollection $cargos): array
    {
        $clasificado = collect();
    
        $cargos->each(function ($cargo) use ($clasificado) {
            $prioridad = $cargo->concepto->prioridad_abono;
            $monto_total = $cargo->monto + $cargo->iva;
    
            // Inicializar la entrada para esta prioridad si no existe
            if (!$clasificado->has($prioridad)) {
                $clasificado->put($prioridad, [
                    'cargos' => collect(),
                    'total' => 0,
                ]);
            }
    
            // Acceder al valor actual de la prioridad
            $prioridadData = $clasificado->get($prioridad);
    
            // Sumar al total por prioridad
            $prioridadData['total'] += $monto_total;
    
            // Agregar el cargo a la colección de la prioridad, manejando abonable
            if ($cargo->concepto->abonable == 0) {
                $prioridadData['cargos']->push($cargo);
            } else {
                $prioridadData['cargos']->prepend($cargo);
            }
    
            // Actualizar la entrada en la colección clasificado
            $clasificado->put($prioridad, $prioridadData);
        });
    
        // Ordenar los elementos de prioridad 2 con prioridad_por_antiguedad en base a la fecha_cargo
        if ($clasificado->has(2)) {
            $prioridadData = $clasificado->get(2);
            $prioridadData['cargos'] = $prioridadData['cargos']->sortBy(function ($cargo) {
                return strtotime($cargo->fecha_cargo);
            });
    
            // Actualizar la entrada en la colección clasificado
            $clasificado->put(2, $prioridadData);
        }
    
        // Asegurar que las prioridades estén ordenadas
        $clasificado = $clasificado->sortKeys();
    
        // Convertir las colecciones de cargos en arrays y devolver los resultados
        $clasificado = $clasificado->map(function ($item) {
            return [
                'cargos' => $item['cargos']->values()->all(), // Asegura que los índices sean correctos
                'total' => $item['total'],
            ];
        });
    
        return $clasificado->all();
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
        }
        catch(Exception $ex){
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
        try{
            DB::beginTransaction();
            // tipo pago
            $modelo = $_modelo;
            $id_modelo = $_id_modelo;
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if($modelo && $id_modelo){
                if($modelo == 'usuario'){
                    $dueno = Usuario::findOrFail($id_modelo);
                }else if($modelo == 'toma'){
                    $dueno = Toma::findOrFail($id_modelo);
                }else{
                    throw new Exception('modelo definido incorrectamente');
                }
                // consolidar estados pagos y cargos
                $estado_pagos = $this->consolidarEstadosDePago($dueno);
                $estado_cargos = $this->consolidarEstadosDeCargo($dueno);
                if($estado_pagos == null && $estado_cargos == null){
                    DB::commit();
                }
                else{
                    $error = " ";
                    if($estado_pagos){
                        $error = $error . " " . $estado_pagos;
                    }
                    if($estado_cargos){
                        $error = $error . " " . $estado_cargos;
                    }
                    throw new Exception('error en la consolidacion de estados: '.$error);
                }
            }
            else{
                throw new Exception('modelo no definido');
            }
        }catch(Exception $ex){
            DB::rollBack();
            throw $ex;
        }
    }

    public function consolidarEstadosDePago($dueno){
        try{
            // carga todos los pagos pendientes
            $pagos = $dueno->pagosPendientes;
            if ($pagos) {
                // se recorren todos los pagos pendientes
                foreach ($pagos as $pago) {
                    // cada pago puede tener abonos
                    $total_abonado = 0;
                    $total_pagado = $pago->total_pagado;
                    $abonos_aplicados = $pago->abonos;

                    // se recorren los abonos realizados para saber
                    // si queda saldo por aplicar de los pagos
                    if($abonos_aplicados){
                        foreach ($abonos_aplicados as $abono) {
                            $total_abonado += $abono->total_abonado;
                        }
                        // después de recorrer todos los abonos
                        $diferencia = abs($total_abonado - $total_pagado);
                        if($diferencia < 1 || $total_abonado == $total_pagado){
                            // si la diferencia es menor a 1
                            $pago_modificado = Pago::findOrFail($pago->id);
                            $pago_modificado->update([
                                'estado' => 'abonado'
                            ]);
                            $pago_modificado->save();
                        }
                        else if($total_abonado < $total_pagado){
                            //throw new Exception('menor abono'.$total_abonado.'pago'.$total_pagado);
                            // si la suma de abonos es menor al total pagado
                            $pago_modificado = Pago::findOrFail($pago->id);
                            $pago_modificado->update([
                                'estado' => 'pendiente'
                            ]);
                            $pago_modificado->save();
                        }
                        else{
                            throw new Exception('error abono'.$total_abonado.'pago'.$total_pagado);
                        }
                    }else{
                        throw new Exception('no hay abonos');
                    }
                }
            }else{
                throw new Exception('no hay pagos');
            }
            return null;
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function consolidarEstadosDeCargo($dueno){
        try{
            // carga todos cargos pendientes
            $cargos = $dueno->cargosVigentes;
            if ($cargos) {
                // se recorren todos los pagos pendientes
                foreach ($cargos as $cargo) {
                    // cada cargo puede tener abonos
                    $total_abonado_al_cargo = 0;
                    $total_cargo = $cargo->monto + $cargo->iva;
                    $abonos_al_cargo = $cargo->abonos;
                    if($abonos_al_cargo){
                        foreach ($abonos_al_cargo as $abono) {
                            $total_abonado_al_cargo += $abono->total_abonado;
                        }
                        // después de recorrer todos los abonos
                        $diferencia = abs($total_abonado_al_cargo - $total_cargo);
                        if($diferencia < 1){
                            // si la diferencia es menor a 1
                            $cargo_modificado = Cargo::findOrFail($cargo->id);
                            $cargo_modificado->update([
                                'estado' => 'pagado'
                            ]);
                            $cargo_modificado->save();
                        }
                        else if($total_abonado_al_cargo < $total_cargo){
                            // si la suma de abonos es menor al total del cargo
                            $cargo_modificado = Cargo::findOrFail($cargo->id);
                            $cargo_modificado->update([
                                'estado' => 'pendiente'
                            ]);
                            $cargo_modificado->save();
                        }
                    }else{
                        throw new Exception('no hay abonos');
                    }
                }
            }else{
                throw new Exception('no hay cargos');
            }
            return null;
        }catch(Exception $ex){
            return $ex;
        }
    }

    public function generarAbonos(){
        
    }

    // metodo para buscar un pago por su id
    public function busquedaPorId($id): Pago
    {
        try{
            return Pago::findOrFail($id);
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // metodo para buscar todos los pagos de un modelo especifico
    public function pagosPorModelo(Request $request){
        try{
            $data = $request->all();

            // tipo pago
            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if($modelo == 'usuario'){
                $dueno = Usuario::findOrFail($id_modelo);
                $pagos = $dueno->pagos;
                if($pagos){
                    return $pagos;
                } else{
                    throw new Exception('el modelo no contiene pagos');
                }
            }else if($modelo == 'toma'){
                $dueno = Toma::findOrFail($id_modelo);
                $pagos = $dueno->pagos;
                if($pagos){
                    return $pagos;
                } else{
                    throw new Exception('el modelo no contiene pagos');
                }
            }else{
                throw new Exception('modelo no definido');
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // metodo para buscar todos los pagos de un modelo especifico
    public function pagosPorModeloPendiente(Request $request)
    {
        try{
            $data = $request->all();

            // tipo pago
            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if($modelo == 'usuario'){
                $dueno = Usuario::findOrFail($id_modelo);
                $pagos = $dueno->pagosPendientes;
                if($pagos){
                    return $pagos;
                } else{
                    throw new Exception('el modelo no contiene pagos');
                }
            }else if($modelo == 'toma'){
                $dueno = Toma::findOrFail($id_modelo);
                $pagos = $dueno->pagosPendientes;
                if($pagos){
                    return $pagos;
                } else{
                    throw new Exception('el modelo no contiene pagos');
                }
            }else{
                throw new Exception('modelo no definido');
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // actualizar pago
    public function modificarPago(UpdatePagoRequest $request, $id): Pago
    {
        try{
            $data = $request->validated();
            $pago = Pago::findOrFail($id);
            $pago->update($data);
            $pago->save();
            return $pago;
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // eliminar pago
    public function eliminarPago($id)
    {
        try{
            $pago = Pago::findOrFail($id);
            $pago->delete();
            return "Pago eliminado con exito";
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // aplicación del pago sobre abono en los cargos
    public function totalPendiente(Request $request)
    {
        try{
            $pagos = $this->pagosPorModeloPendiente($request);
            $total_pendiente = 0;
            foreach ($pagos as $pago) {
                $total_pendiente += $pago->pendiente();
            }
            return $total_pendiente;
        } catch(Exception $ex){
            throw $ex;
        }
    }
}