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
            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if($modelo == 'usuario'){
                $dueno = Usuario::findOrFail($id_modelo);
                $cargos = $dueno->cargos;
                if($cargos){
                    return $cargos;
                } else{
                    throw new Exception('el modelo no contiene cargos');
                }
            }else if($modelo == 'toma'){
                $dueno = Toma::findOrFail($id_modelo);
                $cargos = $dueno->cargos;
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
            $modelo = $data['modelo_dueno'];
            $id_modelo = $data['id_dueno'];
            // si el modelo contiene un valor, entonces se determina
            // el tipo de modelo al que pertenece el pago
            $dueno = null;
            if($modelo == 'usuario'){
                $dueno = Usuario::findOrFail($id_modelo);
                $cargos = $dueno->cargosVigentes;
                if($cargos){
                    return $cargos;
                } else{
                    throw new Exception('el modelo no contiene cargos');
                }
            }else if($modelo == 'toma'){
                $dueno = Toma::findOrFail($id_modelo);
                $cargos = $dueno->cargosVigentes;
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
    public function cargosDeTomasDeUsuario($id){
        try{
            $id_usuario = $id;

            $dueno = null;
            if($id_usuario){
                $dueno = Usuario::with([
                    'tomas',
                    'cargosVigentes',
                    'tomas.cargosVigentes',
                ])->findOrFail($id_usuario);
                return $dueno;
            } else{
                throw new Exception('usuario no definido');
            }
                /*else if($modelo == 'toma'){
                $dueno = Toma::findOrFail($id_modelo);
                $cargos = $dueno->cargos->where('estado', 'pendiente');
                if($cargos){
                    return $cargos;
                } else{
                    throw new Exception('el modelo no contiene cargos');
                }
            }else{
                throw new Exception('modelo no definido');
            }*/
        } catch(Exception $ex){
            throw $ex;
        }
    }
}