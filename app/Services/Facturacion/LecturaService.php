<?php
namespace App\Services\Facturacion;

use App\Models\Lectura;
use Exception;

class LecturaService
{
    public function buscarLecturas($data)
    {
        try {
            $lecturas = null;
            if ($data) {
                // Aquí puedes agregar filtros si es necesario usando los datos de $data
                // Ejemplo: $lecturas = Lectura::where('campo', $data['valor'])->with([...])->get();
            } else {
                $lecturas = Lectura::with([
                    'operador',
                    'toma',
                    'periodo',
                    //'origen',
                    'anomalia'
                ])->get();
            }
            return $lecturas;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function busquedaPorId($id)
    {
        try {
            return Lectura::with([
                'operador',
                'toma',
                'periodo',
                //'origen',
                'anomalia'
            ])->find($id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function registrarLectura($data){
        try{
            $lectura = null;
            if($data){
                $lectura = Lectura::create($data);
                if($lectura){} else{
                    throw new Exception("fallo al registrar la lectura");
                }
            }else{}
            return $lectura;
        }catch(Exception $ex){
            throw $ex;
        }
    }
}