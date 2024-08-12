<?php
namespace App\Services;

use App\Models\Cargo;
use App\Models\Toma;
use App\Models\Usuario;
use BaconQrCode\Common\Mode;
use Exception;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Bool_;

class UsuarioService{

    public function store(array $usuario): Usuario{
        $usuario['codigo_usuario']=Usuario::max('codigo_usuario')+1;
        $user=Usuario::create($usuario);
        return $user;
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

    public function ConsultarSaldoUsuario ($id)
    {
        try {
            DB::beginTransaction();
            $saldos = Cargo::with('dueño' , 'abonos' , 'dueño.cargosVigentes' , 'dueño.usuario')
            ->whereHas('dueño.usuario', function ($query) use($id) {
                $query->where('id' , $id);
            })
            ->distinct()->get();

            $saldoTotalPorUsuario = $saldos->groupBy('dueño.usuario.id')->map(function ($items) {
                $usuario = $items->first()->dueño->usuario;
                
                $cargosVigentes = $items->flatMap(function ($item) {
                    return $item->dueño->cargosVigentes;
                });
                
                $abonos = $items->flatmap(function ($item){
                    return $item->abonos;
                });

                $aux = 0; //Auxiliar para los abonos
                foreach ($abonos as $abono) {
                    $aux += $abono->total_abonado;
                }     
                $saldoTotal = $cargosVigentes->unique('id')->sum('monto');
                $total = 0;
                if ($saldoTotal == 0) {
                    return [
                        'message' => 'El usuario no tiene cargos vigentes' ,
                        'usuario' => $usuario,
                        'cargos pendientes' => $saldoTotal,
                        'abonos' => $aux,
                    ];
                }
                else{
                    $total = $saldoTotal - $aux;
                    return [
                        'usuario' => $usuario,
                        'cargos pendientes' => $saldoTotal,
                        'abonos' => $aux,
                        'saldo_total' => $total,
                    ];
                }      
            });
            
            return $saldoTotalPorUsuario;
        } catch (Exception $ex) {
            DB::rollBack();
            throw $ex;
        }
    }


    

}