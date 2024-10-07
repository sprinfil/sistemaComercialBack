<?php
namespace App\Services\Facturacion;

use App\Models\Factura;
use App\Models\Libro;
use App\Models\Periodo;
use App\Models\Ruta;
use Carbon\Carbon;
use COM;
use Database\Seeders\LibroSeeder;
use ErrorException;
use Exception;


class PeriodoService{

    public function storePeriodo($per){

        $abierto=Periodo::whereIn('id_ruta',array_column($per,"id_ruta"))->where('estatus','activo')->get();
        //return $abierto->pluck('id_ruta');
        if(count($abierto)!=0){
            $mensaje=null;
            $ruta=Ruta::whereIn('id',$abierto->pluck('id_ruta'))->get();
            foreach ($ruta as $rutaP){
                $mensaje=$mensaje.$rutaP->nombre." ";
            }
            throw new ErrorException("Ya existe periodo vigente para las siguentes rutas: ".$mensaje,400);
        }
        else{
            //$periodo=Periodo::insert($insercion);
            $tarifa=(new TarifaService())->TarifaVigente()->id;
            $fecha=Carbon::parse(helperFechaAhora(),'GMT-7');
            $insercion = array_map(function($item)use($tarifa,$fecha) {
                $item['estatus'] = 'activo';
                $item['nombre'] = $fecha->monthName." ".$fecha->year; //mes y año
                $item['periodo'] = $fecha->startOfMonth()->format('Y-m-d'); //mes y año
                $item['id_tarifa'] = $tarifa; 
                return $item;
            }, $per);
        
            $periodo=Periodo::whereIn('id_ruta',array_column($per,"id_ruta"))->where('estatus','activo')->get();
            
            $ruta=Ruta::with('Libros:id,id_ruta,nombre')->whereIn('id',array_column($per,"id_ruta"))->get();
           $carga_trabajo=[];
            foreach ($ruta as $r){
               $libros=$r->libros;
               foreach ($libros as $l){
                $carga['id_libro']=$l['id'];
                $carga_trabajo[]=$carga;
            }
              
            }
            return $carga_trabajo;
            
            //foreach 
            Periodo::insert($insercion);
            return $periodo;
        }   
             

      
    }
    public function updatePeriodo($per,$id){
        $periodo=Periodo::find($id);
        $periodo->update($per);
        $periodo->save();
        return $periodo;
    }

}