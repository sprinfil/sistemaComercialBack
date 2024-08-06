<?php
namespace App\Services;

use App\Http\Requests\StorePagoRequest;
use App\Http\Requests\UpdatePagoRequest;
use App\Http\Resources\PagoResource;
use App\Models\Abono;
use App\Models\CatalogoBonificacion;
use App\Models\Pago;
use Exception;
use Illuminate\Database\Eloquent\Collection;
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

            DB::beginTransaction();

            // crea el registro del pago con los datos ingresados
            $pago = Pago::create($data);
            $monto_pagado = $pago->total_pagado;
            $total_abonado = 0;
            $total_bonificado = 0; //TO DO

            // tipo pago
            $modelo = $data['modelo_dueño'];
            $id_modelo = $data['id_dueño'];

            // valida si el pago cuenta con abonos cargados directamente
            if(isset($data['abonos']) && !is_null($data['abonos'])){
                // se registran los abonos cargados al pago
                foreach ($data['abonos'] as $abono) {
                    $nuevo_abono = new Abono();
                    // se define el cargo al que abona el pago
                    $nuevo_abono->id_cargo = $abono['id_cargo'];
                    // valida que el cargo al que se abona pertenezca al usuario
                    // y su estado sea pendiente de pago
                    
                    // se define el origen del abono (en este caso un pago)
                    $nuevo_abono->id_origen = $pago->id;
                    $nuevo_abono->modelo_origen = 'pago';
                    // se valida que el total del pago no sea menor que el abono
                    if($total_abonado <= $monto_pagado){
                        $total_abonado += $abono['total_abonado'];
                        $nuevo_abono->total_abonado = $abono['total_abonado'];
                    }else{
                        throw new Exception();
                    }
                    // si nada fallo, se guarda el abono
                    $nuevo_abono->save();
                }
            }else{
                // no hay abonos en el pago ingresado
            }

            // valida si el pago aplica alguna bonificacion
            if(isset($data['bonificacion']) && !is_null($data['bonificacion'])){
                //TO DO
            } else{
                // no hay bonificaciones
            }

            if($monto_pagado > $total_abonado){ // + bonificaciones
                $data['estado'] = 'pendiente';
                $pago_modificado = Pago::findOrFail($pago->id);
                $pago_modificado->update($data);
                $pago_modificado->save();
            } else if($monto_pagado == $total_abonado){ // + bonificaciones
                $data['estado'] = 'abonado';
                $pago_modificado = Pago::findOrFail($pago->id);
                $pago_modificado->update($data);
                $pago_modificado->save();
            } else if($monto_pagado < $total_abonado){ // + bonificaciones
                throw new Exception("calculo de saldos");
            }

            DB::commit();
            return $pago;
        } catch(Exception $ex){
            DB::rollBack();
            throw $ex;
        }
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