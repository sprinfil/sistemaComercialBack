<?php
namespace App\Services\Caja;

use App\Http\Requests\StoreCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use App\Models\Cargo;
use App\Models\Toma;
use App\Models\Usuario;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CargoService{

    // metodo para obtener todos los cargos registrados
    public function obtenerCargos(): Collection
    {
        try{
            return Cargo::all();
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // metodo para cargar un cargo a un usuario/toma
    public function generarCargo(StoreCargoRequest $request): Cargo
    {
        try{
            $data = $request->validated();
            return Cargo::create($data);
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // metodo para buscar un cargo por id
    public function busquedaPorId($id): Cargo
    {
        try{
            return Cargo::findOrFail($id);
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // actualizar cargo
    public function modificarCargo(UpdateCargoRequest $request, $id): Cargo
    {
        try{
            $data = $request->validated();
            $cargo = Cargo::findOrFail($id);
            $cargo->update($data);
            $cargo->save();
            return $cargo;
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // eliminar cargo
    public function eliminarCargo($id)
    {
        try{
            $cargo = Cargo::findOrFail($id);
            $cargo->delete();
            return "Cargo eliminado con exito";
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // metodo para buscar todos los pagos de un modelo especifico
    public function cargosPorModelo(Request $request){
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
                $cargos = $dueño->cargos;
                if($cargos){
                    return $cargos;
                } else{
                    throw new Exception('el modelo no contiene cargos');
                }
            }else if($modelo == 'toma'){
                $dueño = Toma::findOrFail($id_modelo);
                $cargos = $dueño->cargos;
                if($cargos){
                    return $cargos;
                } else{
                    throw new Exception('el modelo no contiene cargos');
                }
            }else{
                throw new Exception('modelo no definido');
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // metodo para buscar todos los pagos de un modelo especifico
    public function cargosPorModeloPendientes(Request $request){
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
                $cargos = $dueño->cargos->where('estado', 'pendiente');
                if($cargos){
                    return $cargos;
                } else{
                    throw new Exception('el modelo no contiene cargos');
                }
            }else if($modelo == 'toma'){
                $dueño = Toma::findOrFail($id_modelo);
                $cargos = $dueño->cargos->where('estado', 'pendiente');
                if($cargos){
                    return $cargos;
                } else{
                    throw new Exception('el modelo no contiene cargos');
                }
            }else{
                throw new Exception('modelo no definido');
            }
        } catch(Exception $ex){
            throw $ex;
        }
    }
}