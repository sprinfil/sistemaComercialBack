<?php
namespace App\Services;

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
    public function ConsultaGeneral($codigoUsuario){
        $user=Usuario::where('codigo_usuario',$codigoUsuario)->with('tomas.medidor','tomas.giroComercial','contratos','descuento_asociado')->get();
        return $user;
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

    public function ConsultarSaldoUsuario ($id)
    {
        try{
            $CargosUsuario=Usuario::where('id',$id)->with('tomas','tomas.cargosVigentes')->get();
            return $CargosUsuario;
        }
        catch(Exception $ex){

        }
        /*
        try {
            $saldo_total = 0;
              $cargos = Cargo::with('dueño')
                ->where('estado', '=' , 'pendiente')->where('id_dueño',$id);
               
            foreach ($cargos as $cargo) {
               
                  $saldo_total += $cargo->monto;
               
            }
            return response()->json(['monto total' => $saldo_total] , 200);
           
        } catch (Exception $ex) {
                return response()->json([
                    'error' => 'No hay cargos para este usuario.'.$ex
                ], 404);
            
        }
                */
     
                /*
        $saldo_total = 0;
        //Falta sacar el monto pendiente por usuario {id} (modificar la ruta tambien)
        return $usuarios = Cargo::with('dueño')
        ->where('estado' , '=', 'pendiente')
        ->find($id);
        $usuarios->monto;
        foreach ($usuarios as $usuario) {
            $saldo_total += $usuario->monto;
        }
        return $saldo_total;
        
        $cargos = Cargo::with('dueño')->where('estado' , '=', 'pendiente')->first();
        //$cargos->dueño->sum('monto');
        foreach ($cargos as $cargo) {
            $abonos = $cargo->monto;
            $saldo_total += $cargo->monto;
        }
        return $saldo_total;
        */
        

    }
    

}