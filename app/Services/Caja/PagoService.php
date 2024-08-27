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

    // método para cargar un cargo a un usuario
    public function registrarPago(StorePagoRequest $request)
    {
        try {
            $data = $request->validated();

            DB::beginTransaction();

            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            $dueno = helperGetOwner($modelo, $id_modelo);

            $caja = Caja::find($data['id_caja']);
            $numeroPagos = $caja->pagos()->count() + 1;
            $folio = strtoupper('C'.str_pad($caja->id, 2, '0', STR_PAD_LEFT).'P' . str_pad($numeroPagos, 4, '0', STR_PAD_LEFT));
            $data['folio'] = $folio;

            $pago = Pago::create($data);
            $monto_pagado = $pago->total_pagado;

            $pagos_pendientes = $this->pagosPorModeloPendiente($request);
            $monto_pagos_pendientes = $this->totalPendiente($request);

            $cargos_selecionados = $data['cargos'];
            $total_a_pagar = 0;

            if($cargos_selecionados){
                foreach ($cargos_selecionados as $cargo) {
                    $cargo_selecionado = Cargo::findOrFail($cargo['id_cargo']); // Asumiendo que cargo es un array
                    $total_a_pagar += $cargo_selecionado->monto + $cargo_selecionado->iva; // Asumiendo que las propiedades existen
                }

                if($total_a_pagar <= $monto_pagado){
                    $total_abonado = 0;
                    foreach ($cargos_selecionados as $cargo) {
                        $this->registrarAbono($cargo['id_cargo'], 'pago', $pago->id, $cargo_selecionado->monto + $cargo_selecionado->iva);
                        $this->consolidarEstados($id_modelo, $modelo);
                        $total_abonado += $cargo_selecionado->monto + $cargo_selecionado->iva; // Actualizar el total abonado
                    }                    
                } else {
                    if($total_a_pagar < $monto_pagos_pendientes){
                        foreach ($cargos_selecionados as $cargo) {
                            $cargo_real = Cargo::findOrFail($cargo['id_cargo']);
                            foreach($pagos_pendientes as $pago) {
                                $diferencia = abs($pago->pendiente() - $cargo_real->monto);
                                if($pago->pendiente() > $cargo_real->monto && $diferencia < 1){
                                    $this->registrarAbono($cargo['id_cargo'], 'pago', $pago->id, $pago->pendiente());
                                } else if($cargo_real->concepto->abonable == 'true' && $pago->pendiente() < $cargo_real->monto) {
                                    $this->registrarAbono($cargo['id_cargo'], 'pago', $pago->id, $pago->pendiente());
                                }else{
                                    //throw new Exception("No se puede abonar a ese cargo");
                                }
                            }
                            $this->consolidarEstados($id_modelo, $modelo);
                        }   
                    }
                }
            } else {
                throw new Exception("No hay cargos a pagar");
            }

           
          //  $pago = Pago::findOrFail($pago->id);
          $this->consolidarEstados($id_modelo, $modelo);
            DB::commit();
            return Pago::findOrFail($pago->id);
        } 
        catch(Exception $ex){
            DB::rollBack();
            throw $ex;
        }
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
                if($estado_pagos == 1 && $estado_cargos == 1){
                    DB::commit();
                }
                else{
                    throw new Exception('error en la consolidacion de estados');
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
                        if($diferencia < 1){
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
            return 1;
        }catch(Exception $ex){
            return 0;
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
            return 1;
        }catch(Exception $ex){
            return 0;
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