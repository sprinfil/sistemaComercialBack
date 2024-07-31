<?php
namespace App\Services;

use App\Http\Requests\StoreCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use App\Models\Cargo;
use Exception;
use Illuminate\Database\Eloquent\Collection;

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

    // metodo para cargar un cargo a un usuario
    public function generarCargo(StoreCargoRequest $request): Cargo
    {
        try{
            $data = $request->validated();
            return Cargo::create($data);
        } catch(Exception $ex){
            throw $ex;
        }
    }

    // metodo para cargar un cargo a un usuario
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
}