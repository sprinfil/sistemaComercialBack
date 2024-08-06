<?php
namespace App\Services\Catalogos;

use App\Http\Resources\OperadorResource;
use App\Models\Operador;
use App\Models\User;
use COM;
use Exception;
use Illuminate\Http\Client\Request;

class OperadorCatalogoService{


    public function indexOperadorCatalogoService()
    {
       
       try {
        return response(OperadorResource::collection(
            Operador::all()
        ), 200);
       } catch (Exception $ex) {
        return response()->json([
            'message' => 'No se encontraron registros de operadores.'
        ], 200);
       }
        

    }

    public function storeOperadorCatalogoService(string $codEmpleado,array $data)
    {

        try {       
              //Busca por codigo de empleado a los eliminados
              $operador = Operador::withTrashed()->where('codigo_empleado', $codEmpleado)->first();
              //VALIDACION POR SI EXISTE
              
              if ($operador) {
                  if ($operador->trashed()) {
                      return response()->json([
                          'message' => 'El operador ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                          'restore' => true,
                          'operador_id' => $operador->id
                      ], 200);
                  }
                  return response()->json([
                      'message' => 'El operador ya existe.',
                      'restore' => false
                  ], 200);
              }
              //si no existe el concepto lo crea
              if (!$operador) {
                  $operador = Operador::create($data);
                  return response(new OperadorResource($operador), 201);
              }
  
       
         } catch (Exception $ex) {
             return response()->json([
                 'message' => 'Ocurrio un error al registrar el operador.'
             ], 200);
         }
              
    }
    public function storeOperadorCatalogoService_2(array $data)
    {
        try {
            
            $user = new User();
            $user->name = $data["name"];
            $user->email = $data["email"];
            $user->password = bcrypt($data["password"]);
            $user->save();

            $operador = new Operador();
            $operador->id_user = $user->id;
            $operador->codigo_empleado = $data["codigo_empleado"];
            $operador->nombre = $data["nombre"];
            $operador->apellido_paterno = $data["apellido_paterno"];
            $operador->apellido_materno = $data["apellido_materno"];
            $operador->CURP = $data["CURP"];
            $operador->fecha_nacimiento = $data["fecha_nacimiento"];
            $operador->save();

            return response(new OperadorResource($operador), 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se guardo el operador'
            ], 500);
        }
    }

    
    public function showOperadorCatalogoService(string $id)
    {
        try {
            $operador = Operador::findOrFail($id);
            return response(new OperadorResource($operador), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se encotro el operador'
            ], 500);
        }
    }

    

    public function updateOperadorCatalogoservice(array $data, string $id)
    {
               //pendiente validacion que permite solucionar repetidos por modificaciones sin update request
        try {            
                
                if ($data != null) {               
                    $operador = Operador::findOrFail($id);
                    $operador->update($data);
                    $operador->save();
                    return response(new OperadorResource($operador), 200);
                }
                else{
                    return response()->json([
                        'error' => 'No se edito el operador'
                    ], 500);
                }                         
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al modificar el operador.'
            ], 200);
        }        
              
    }

    public function updateOperadorCatalogoservice_2(array $data, string $id_user, string $id_operador)
    {
               //pendiente validacion que permite solucionar repetidos por modificaciones sin update request
        try {            
                
            $user = User::find($id_user);
            $user->name = $data["name"];
            $user->email = $data["email"];
            if ($data["password"]) {
                $user->password = bcrypt($data["password"]);
            }
            $user->save();

            $operador = Operador::find($id_operador);
            $operador->id_user = $user->id;
            $operador->codigo_empleado = $data["codigo_empleado"];
            $operador->nombre = $data["nombre"];
            $operador->apellido_paterno = $data["apellido_paterno"];
            $operador->apellido_materno = $data["apellido_materno"];
            $operador->CURP = $data["CURP"];
            $operador->fecha_nacimiento = $data["fecha_nacimiento"];
            $operador->save();  
            return response(new OperadorResource($operador), 201);            
        } catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al modificar el operador.'
            ], 200);
        }        
              
    }

    public function destroyOperadorCatalogoService(string $id)
    {      
        try {          
            $operador = Operador::findOrFail($id);
            $operador->delete();
            return response("Operador eliminado con exito", 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al borrar el operador.'
            ], 500);
        }      
    }

    public function restaurarOperadorCatalogoService (string $id)
    {
        try {
            $operador = Operador::withTrashed()->findOrFail($id);

             // Verifica si el registro está eliminado
             if ($operador->trashed()) {

               // Restaura el registro
               $operador->restore();
               return response()->json(['message' => 'El operador ha sido restaurado.'], 200);
            }
        
        }catch (Exception $ex) {
            return response()->json([
                'message' => 'Ocurrio un error al restaurar el operador.'
            ], 200);         
        }
       
    }

}