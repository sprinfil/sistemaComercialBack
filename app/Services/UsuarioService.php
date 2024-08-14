<?php
namespace App\Services;

use App\Http\Resources\DatoFiscalResource;
use App\Http\Resources\UsuarioResource;
use App\Models\Cargo;
use App\Models\Toma;
use App\Models\Usuario;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;

class UsuarioService{

    public function store(array $usuario): Usuario{
        $usuario['codigo_usuario']=Usuario::max('codigo_usuario')+1;
        $user=Usuario::create($usuario);
        return $user;
    }

    public function storeMoralService(array $request)
    {
        try{
            
            $usuario = Usuario::withTrashed()->where('nombre', $request['nombre'])->orWhere('rfc', $request['rfc'])->orWhere('correo', $request['correo'])->first();
            $request['codigo_usuario']=Usuario::max('codigo_usuario')+1;
            //VALIDACION POR SI EXISTE
            if ($usuario) {
                if ($usuario->trashed()) {
                    return response()->json([
                        'message' => 'El usuario ya existe pero ha sido eliminado. ¿Desea restaurarlo?',
                        'restore' => true,
                        'usuario_id' => $usuario->id
                    ], 200);
                }
                return response()->json([
                    'message' => 'El usuario ya existe.',
                    'restore' => false
                ], 200);
            }
            //si no existe el usuario lo crea
            if(!$usuario)
            {
                $usuario=Usuario::create($request); //era $data
                return response(new UsuarioResource($usuario),201);
            }
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'El usuario ya existe.'.$ex,
                'restore' => false
            ], 200);
        }
    }


    public function ConsultaGeneral($id){
        $user=Usuario::find($id);
        return $user;
    }
    public function ConsultaGeneralToma($id){
        $toma=Toma::findOrFail($id); //->with('usuario')
        $toma->usuario;
        $toma->giroComercial;
        $toma->contratoVigente;
        $toma->datos_fiscales;
        return $toma;
    }
    public function TomasUsuario($id): Collection{
        $tomas=Usuario::find($id)->tomas;
        return $tomas;
    }
    public function UsuarioCodigoToma($id){
        $tomas=Toma::where('id_codigo_toma',$id)->with('usuario')
        ->paginate(10);
        return $tomas;
    }
    public function DireccionToma($direccion){
        $numero_casa = preg_replace('/\D/', '', $direccion);
        if (!$numero_casa){
            $toma=Toma::whereRaw("
                CONCAT(
                    COALESCE(calle, ''), ' ', 
                    COALESCE(entre_calle_1, ''), ' ', 
                    COALESCE(entre_calle_2, ''), ' ', 
                    COALESCE(colonia, ''), ' ',
                    COALESCE(localidad, '') 
                )  LIKE ?", ['%'.$direccion.'%'])
                ->with('usuario')
                ->paginate(10);
        } 
        else{
            $toma=Toma::whereRaw ("
                CONCAT(
                    COALESCE(calle, ''), ' ', 
                    COALESCE(numero_casa, '')
                )  LIKE ?", ['%'.$direccion.'%'])
                ->with('usuario')
                ->paginate(10);
        }
        
        return $toma;
    }
    //obtiene el saldo para un modelo
    public function ConsultarSaldo($modelo){
        try {
            $total=0;
            $cargos=$modelo->cargosVigentes;       
                foreach ($cargos as $cargo){
                    $total+=$cargo->monto;
                    $abonos=$cargo->abonos;
                        foreach ($abonos as $abono){
                            $total-=$abono->total_abonado;
                        }
                    }
            return $total;
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No fue posible consultar el saldo '.$ex
            ], 500);
        }

    }
    //consulta todas las tomas del usuario y obtiene sus saldos y le suma los saldos del usuario
    public function TotalSaldoUsuario($id)
    {
        try{   
            $Usuario=Usuario::find($id);
                $tomas=$Usuario->tomas;
                $total=0;
                foreach ($tomas as $toma){
                    $total+=$this->ConsultarSaldo($toma);
                }
                $totalUsuario=$Usuario->saldoCargosUsuario();
                $total+=$totalUsuario;
                return $total;            
        }
        catch(Exception $ex){
            return response()->json([
                'error' => 'No fue posible consultar el saldo '.$ex
            ], 500);
        }
           
    }


    public function updateUsuarioService(array $data, string $id)
    {
        try{
            $usuario=Usuario::find($id);
            $usuario->update($data);
            $usuario->save();
            return new UsuarioResource($usuario);
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo modificar el usuario, introduzca datos correctos'], 200);
        }
       
    }

    public function updateMoralUsuarioService(array $data, string $id)
    {
        try{
            $usuario=Usuario::find($id);
            $usuario->update($data);
            $usuario->save();
            return new UsuarioResource($usuario);
        }
        catch(Exception $ex){
            return response()->json(['error' => 'No se pudo modificar el usuario, introduzca datos correctos'], 200);
        }
    }

    public function destroyUsuarioService(string $id)
    {
        try
        {
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();
            return response()->json(['message' => 'Eliminado correctamente'], 200);
        }
        catch (\Exception $e) {

            return response()->json(['message' => 'error'], 500);
        }
    }
    
    public function restaurarDatoUsuarioService(string $id)
    {
        try {
            $Usuario = Usuario::withTrashed()->findOrFail($id);

            // Verifica si el registro está eliminado
            if ($Usuario->trashed()) {
                // Restaura el registro
                $Usuario->restore();
                return response()->json(['message' => 'El usuario ha sido restaurado.'], 200);
            }
        } catch (Exception $ex) {
            return response()->json(['message' => 'error'], 500);
        }
       

    }

    public function datosFiscalesUsuarioService($id)
    {
        try{
            $usuario = Usuario::findOrFail($id);
            if($usuario){
                $datos_fiscales = $usuario->datos_fiscales()->first();
                return new DatoFiscalResource($datos_fiscales);
            }
            return response()->json(['message' => 'error'], 500);
        } catch(Exception $ex) {
            return response()->json(['message' => 'error'.$ex], 500);
        } 
    }

    public function storeOrUpdateDatosFiscalesService(array $validatedData, $id)
    {
        try{
                // Encontrar el usuario
                $usuario = Usuario::find($id);

                if ($usuario) {
                    $polymorphicData = [
                        'id_modelo' => $usuario->id,
                        'modelo' => get_class($usuario)
                    ];
            
                    // Obtener o crear los datos fiscales
                    $datosFiscales = $usuario->datos_fiscales()->updateOrCreate(
                        $polymorphicData,
                        $validatedData
                    );
            
                    // Retornar los datos fiscales actualizados o creados
                    return new DatoFiscalResource($datosFiscales);
                }
                return response()->json(['message' => 'error no se encontro usuario'], 500);
            }
            catch(Exception $ex) {
                return response()->json(['message' => 'error'.$ex], 500);
            } 
    }
}