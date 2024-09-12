<?php
namespace App\Services;

use App\Models\Cargo;
use App\Models\Toma;
use Exception;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Collection;
use PhpParser\Node\Expr\FuncCall;

class TomaService{
    /*
    public function crearToma(){
        ///generar código de toma
    }
        */
    public function tomaTipos($filtros){
        $ruta=$filtros['ruta_id'] ?? null;
        $libro=$filtros['libro_id'] ?? null;
        $toma=$filtros['toma_id'] ?? null;
        $saldoMin=$filtros['saldo_min'] ?? null;
        $saldoMax=$filtros['saldo_max'] ?? null;
        $domestica=$filtros['domestica'] ?? null;
        $comercial=$filtros['comercial'] ?? null;
        $industrial=$filtros['industrial'] ?? null;
        $especial=$filtros['especial'] ?? null;
        $sin_contrato=$filtros['sin_contrato'] ?? null;
        $codigo=$filtros['codigo_toma'] ?? null;

         // HIPER MEGA QUERY INSANO
         $query=Toma::with('usuario','tipoToma','libro','ruta')
        ->when($ruta, function (Builder $q) use($ruta,$libro)  {

        $q->when($libro, function (Builder $a2) use($ruta,$libro){
            $a2->with('libro')->whereHas('libro', function($b)use($ruta,$libro){
                $b->where('id',$libro)->with('tieneRuta')->whereHas('tieneRuta', function($c)use($ruta){
                    $c->where('id',$ruta);
                    
                });
            });
        },function (Builder $a3)use($ruta){
            $a3->with('libro')->whereHas('libro', function($b)use($ruta){
                $b->with('tieneRuta')->whereHas('tieneRuta', function($c)use($ruta){
                    $c->where('id',$ruta);
                    
                });
            });
        });
        
    })->when($toma, function (Builder $q) use($toma,$domestica,$comercial,$industrial,$especial,$sin_contrato) {
            
            $q->whereHas('tipoToma', function($a)use($domestica,$comercial,$industrial,$especial,$sin_contrato){
                $types = [];

                if ($domestica) {
                    $types[] = 'Domestica';
                }
                if ($comercial) {
                    $types[] = 'Comercial';
                }
                if ($industrial) {
                    $types[] = 'Industrial';
                }
                if ($especial) {
                    $types[] = 'Especial';
                }
                if ($sin_contrato) {
                    $types[] = 'Sin Contrato';
                }
        
                if (!empty($types)) {
                    $a->whereIn('nombre', $types);
                }
                
            });
            $q->where('id_toma',$toma);
            
        }
        ,function(Builder $q)use($domestica,$comercial,$industrial,$especial,$sin_contrato){
            $q->whereHas('tipoToma', function($a)use($domestica,$comercial,$industrial,$especial,$sin_contrato){
                
                $types = [];

                if ($domestica) {
                    $types[] = 'Domestica';
                }
                if ($comercial) {
                    $types[] = 'Comercial';
                }
                if ($industrial) {
                    $types[] = 'Industrial';
                }
                if ($especial) {
                    $types[] = 'Especial';
                }
                if ($sin_contrato) {
                    $types[] = 'Sin Contrato';
                }
        
                if (!empty($types)) {
                    $a->whereIn('nombre', $types);
                }
            });
        })->when($codigo, function (Builder $q) use ($codigo){
            $q->where('codigo_toma',$codigo);
        })
        ->get();

        //TODO CONSULTA SALDO CON Y SIN CONVENIO
/*
        if ($saldoMin){
            if ($saldoMax){
                $query = $query->filter(function($query) use($saldoMin,$saldoMax) {
                    if (!empty($query)){
                        $saldo=$query->saldoToma();
                        if ($saldo>=$saldoMin && $saldo<=$saldoMax){
                            $toma['saldo']=$saldo;
                            unset($toma['cargosVigentes']);
                            
                            $resultado=$toma;
                    
                            return $resultado;
                        }
                    }
                    
                    
            
                });
            }
            
            else{
              return null;
            }
                
        
            //return $tomasSaldo;
        }
            */
            
            $Querysaldo=new Collection();
            foreach($query as $ot){
                $saldo=$ot->saldoToma();
                $ot->saldo=$saldo;
       
                if ($saldoMin){
                    if ($saldoMax){
                        if ($ot['saldo']>=$saldoMin && $ot['saldo']<=$saldoMax){
                    
                            $Querysaldo->push($ot);
                         
                        }
                    }
                    
                    else{
                        if ($ot['saldo']==$saldoMin){
                            $Querysaldo->push($ot);
                        }
                    }
                
                }
                else{
                    $Querysaldo->push($ot);
                }
                unset( $ot['cargosVigentes']);
               
            }
                
        $OT =$Querysaldo;
        return $OT;
        //

    }
    public function generarCodigoToma($libro){
        $nombre_libro = $libro->nombre;
                    $ruta_sel = $libro->tieneRuta;
                    $nombre_ruta = $ruta_sel->nombre;
    
                    // Usar una expresión regular para encontrar los números
                    preg_match('/\d+/', $nombre_libro, $coincidencias_libro);
                    preg_match('/\d+/', $nombre_ruta, $coincidencias_ruta);
    
                    $numero_libro = isset($coincidencias_libro[0]) ? (int)$coincidencias_libro[0] : null;
                    $numero_ruta = isset($coincidencias_ruta[0]) ? (int)$coincidencias_ruta[0] : null;
    
                    // Generar el folio
                    $folio = strtoupper(
                        ''.str_pad($numero_ruta, 2, '0', STR_PAD_LEFT).''.str_pad($numero_libro, 2, '0', STR_PAD_LEFT).''.str_pad($libro->countTomas()+1, 3, '0', STR_PAD_LEFT)
                    );
                    return $folio;
    }
}