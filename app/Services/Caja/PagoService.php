<?php
namespace App\Services\Caja;

use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Http\Resources\PagoResource;
use App\Models\Abono;
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

    // metodo para cargar un cargo a un usuario
    public function registrarPago(StorePagoRequest $request): Pago
    {
        try{
            // se validan los datos
            $data = $request->validated();
            $data['estado'] = 'pendiente';

            DB::beginTransaction();

            // crea el registro del pago con los datos ingresados
            $pago = Pago::create($data);
            $monto_pagado = $pago->total_pagado;
            $total_abonado = 0;
            $total_bonificado = 0; //TO DO

            // tipo pago
            $modelo = $data['modelo_dueño'];
            $id_modelo = $data['id_dueño'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueño = null;
            if($modelo == 'usuario'){
                $dueño = Usuario::findOrFail($id_modelo);
            }else if($modelo == 'toma'){
                $dueño = Toma::findOrFail($id_modelo);
            }else{
                throw new Exception('modelo no definido');
            }

            // valida si el pago cuenta con abonos cargados directamente
            if (isset($data['abonos']) && !is_null($data['abonos'])) {
                // se consultan los cargos pendientes
                $cargos = $dueño->cargosVigentes;

                if ($cargos) {
                    // se registran los abonos cargados al pago
                    foreach ($data['abonos'] as $abono) {
                        $nuevo_abono = new Abono();
                        // se define el cargo al que abona el pago
                        $nuevo_abono->id_cargo = $abono['id_cargo'];

                        // valida que el cargo al que se abona pertenezca al usuario
                        // y su estado sea pendiente de pago
                        $cargo_valido = false;
                        foreach ($cargos as $cargo) {
                            if ((int) $cargo->id === (int) $abono['id_cargo']) {
                                $cargo_valido = true;
                                break; // Salimos del bucle si encontramos un cargo válido
                            }
                        }
                        if ($cargo_valido == false) {
                            throw new Exception('El cargo ya esta saldado o no corresponde a ' . $modelo . ': ' . $id_modelo);
                        }

                        // se define el origen del abono (en este caso un pago)
                        $nuevo_abono->id_origen = $pago->id;
                        $nuevo_abono->modelo_origen = 'pago';
                        // se valida que el total del pago no sea menor que el abono
                        if ($total_abonado + $abono['total_abonado'] <= $monto_pagado) {
                            $total_abonado += $abono['total_abonado'];
                            $nuevo_abono->total_abonado = $abono['total_abonado'];
                        } else {
                            throw new Exception('El monto del abono excede el monto del pago.');
                        }
                        // si nada falló, se guarda el abono
                        $nuevo_abono->save();
                    }
                } else {
                    throw new Exception('No hay cargos para abonar.');
                }
            } else {
                // no hay abonos en el pago ingresado
            }


            // valida si el pago aplica alguna bonificacion
            if(isset($data['bonificacion']) && !is_null($data['bonificacion'])){
                //TO DO
            } else{
                // no hay bonificaciones
            }

            if($monto_pagado > $total_abonado){ // + bonificaciones
                $this->consolidarEstados($id_modelo, $modelo);
                /*$data['estado'] = 'pendiente';
                $pago_modificado = Pago::findOrFail($pago->id);
                $pago_modificado->update($data);
                $pago_modificado->save();*/
            } else if($monto_pagado == $total_abonado){ // + bonificaciones
                $this->consolidarEstados($id_modelo, $modelo);
                /*$data['estado'] = 'abonado';
                $pago_modificado = Pago::findOrFail($pago->id);
                $pago_modificado->update($data);
                $pago_modificado->save();*/
            } else if($monto_pagado < $total_abonado){ // + bonificaciones
                throw new Exception("calculo de saldos");
            }

            DB::commit();
            $pago->total_abonado = $total_abonado;
            return $pago;
        } catch(Exception $ex){
            DB::rollBack();
            throw $ex;
        }
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
            $dueño = null;
            if($modelo && $id_modelo){
                if($modelo == 'usuario'){
                    $dueño = Usuario::findOrFail($id_modelo);
                }else if($modelo == 'toma'){
                    $dueño = Toma::findOrFail($id_modelo);
                }else{
                    throw new Exception('modelo definido incorrectamente');
                }
                // consolidar estados pagos y cargos
                $estado_pagos = $this->consolidarEstadosDePago($dueño);
                $estado_cargos = $this->consolidarEstadosDeCargo($dueño);
                if($estado_pagos == 1 && $estado_cargos == 1){
                    DB::commit();
                }
                else{
                    throw new Exception('QPD');
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

    public function consolidarEstadosDePago($dueño){
        try{
            // carga todos los pagos pendientes
            $pagos = $dueño->pagosPendientes;
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
                        // despues de recorrer todos los abonos
                        if($total_abonado == $total_pagado){
                            //throw new Exception('igual abono'.$total_abonado.'pago'.$total_pagado);
                            // si la suma de abonos es igual al total pagado
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
                            throw new Exception('qpd abono'.$total_abonado.'pago'.$total_pagado);
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

    public function consolidarEstadosDeCargo($dueño){
        try{
            // carga todos cargos pendientes
            $cargos = $dueño->cargosVigentes;
            if ($cargos) {
                // se recorren todos los pagos pendientes
                foreach ($cargos as $cargo) {
                    // cada cargo puede tener abonos
                    $total_abonado_al_cargo = 0;
                    $total_cargo = $cargo->monto;
                    $abonos_al_cargo = $cargo->abonos;
                    if($abonos_al_cargo){
                        foreach ($abonos_al_cargo as $abono) {
                            $total_abonado_al_cargo += $abono->total_abonado;
                        }
                        // despues de recorrer todos los abonos
                        if($total_abonado_al_cargo == $total_cargo){
                            // si la suma de abonos es igual al total del cargo
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
            $modelo = $data['modelo_dueño'];
            $id_modelo = $data['id_dueño'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueño = null;
            if($modelo == 'usuario'){
                $dueño = Usuario::findOrFail($id_modelo);
                $pagos = $dueño->pagos;
                if($pagos){
                    return $pagos;
                } else{
                    throw new Exception('el modelo no contiene pagos');
                }
            }else if($modelo == 'toma'){
                $dueño = Toma::findOrFail($id_modelo);
                $pagos = $dueño->pagos;
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
    public function pagosPorModeloPendiente(Request $request){
        try{
            $data = $request->all();

            // tipo pago
            $modelo = $data['modelo_dueño'];
            $id_modelo = $data['id_dueño'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueño = null;
            if($modelo == 'usuario'){
                $dueño = Usuario::findOrFail($id_modelo);
                $pagos = $dueño->pagos;
                if($pagos){
                    return $pagos;
                } else{
                    throw new Exception('el modelo no contiene pagos');
                }
            }else if($modelo == 'toma'){
                $dueño = Toma::findOrFail($id_modelo);
                $pagos = $dueño->pagos;
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
}