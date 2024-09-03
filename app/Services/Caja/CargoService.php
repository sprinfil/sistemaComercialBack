<?php
namespace App\Services\Caja;

use App\Http\Requests\StoreCargoDirectoRequest;
use App\Http\Requests\StoreCargoRequest;
use App\Http\Requests\UpdateCargoRequest;
use App\Http\Resources\CargoResource;
use App\Models\Cargo;
use App\Models\CargoDirecto;
use App\Models\ConceptoCatalogo;
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
    public function cargosPorModeloPendientesFormateados(Request $request){
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
                $cargos = $dueno->cargosVigentes()->with('concepto')->get();
                if($cargos){
                    return $cargos;
                } else{
                    throw new Exception('el modelo no contiene cargos');
                }
            }else if($modelo == 'toma'){
                $dueno = Toma::findOrFail($id_modelo);
                $cargos = $dueno->cargosVigentes()->with('concepto')->get();
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

    public function cargosDeTomasDeUsuario($id)
    {
        try {
            $id_usuario = $id;

            if ($id_usuario) {
                $dueno = Usuario::with([
                    'tomas',
                    'cargosVigentes',
                    'tomas.cargosVigentes',
                ])->findOrFail($id_usuario);

                // Añadir el monto pendiente manualmente
                $dueno->cargosVigentes->each(function ($cargo) {
                    $cargo->monto_pendiente = $cargo->montoPendiente();
                });

                $dueno->tomas->each(function ($toma) {
                    $toma->cargosVigentes->each(function ($cargo) {
                        $cargo->monto_pendiente = $cargo->montoPendiente();
                    });
                });

                return $dueno;
            } else {
                throw new Exception('usuario no definido');
            }
        } catch (Exception $ex) {
            throw $ex;
        }
    }


    // metodo para cargar un cargo a un usuario/toma
    public function generarCargoDirecto(StoreCargoDirectoRequest $request)
    {
        try {
            $data = $request->validated();
        
            $id_dueno = $data['id_dueno'];
            $modelo_dueno = $data['modelo_dueno'];
            $id_origen = $data['id_origen'];
            $modelo_origen = $data['modelo_origen'];
        
            $cargo_directo_data = [];
            $cargo_directo_data['id_origen'] = $id_origen;
            $cargo_directo_data['modelo_origen'] = $modelo_origen;
        
            // Crear la entrada en CargoDirecto
            $cargo_directo = CargoDirecto::create($cargo_directo_data);
        
            // Obtener el ID del origen y modelo después de la creación
            $id_origen = $cargo_directo->id;
            $modelo_origen = 'cargo_directo';
        
            // Procesar cada cargo en la lista de "cargos"
            foreach ($data['cargos'] as $cargo_item) {
                $id_concepto_cargado = $cargo_item['id_concepto'];
                $monto = $cargo_item['monto'];
        
                $concepto_cargado = ConceptoCatalogo::findOrFail($id_concepto_cargado);
                $nombre_cargo = '' . $concepto_cargado->nombre;
        
                $iva = $monto ? $monto * 0.16 : 0;
        
                $cargo_directo_data = [
                    'id_concepto' => $id_concepto_cargado,
                    'nombre' => $nombre_cargo,
                    'id_origen' => $id_origen,
                    'modelo_origen' => $modelo_origen,
                    'id_dueno' => $id_dueno,
                    'modelo_dueno' => $modelo_dueno,
                    'estado' => 'pendiente',
                    'monto' => $monto,
                    'iva' => $iva,
                    'fecha_cargo' => now()
                ];
        
                $cargo = Cargo::create($cargo_directo_data);
        
                if (!$cargo) {
                    throw new Exception("Error al crear el cargo");
                }
            }
        
            return response()->json(['data' => new CargoResource($cargo)], 201);
        
        } catch (Exception $ex) {
            throw $ex;
        }
    }
}