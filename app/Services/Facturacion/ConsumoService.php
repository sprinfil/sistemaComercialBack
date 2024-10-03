<?php
namespace App\Services\Facturacion;

use App\Models\Consumo;
use App\Models\Lectura;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class ConsumoService
{
    public function buscarConsumos($data)
    {
        try {
            $consumos = null;
            if ($data) {
                // Aquí puedes agregar filtros si es necesario usando los datos de $data
                // Ejemplo: $lecturas = Lectura::where('campo', $data['valor'])->with([...])->latest()->first();
            } else {
                $consumos = Consumo::with([
                    'toma',
                    'lecturaAnterior',
                    'lecturaActual',
                    //'origen',
                    'periodo'
                ])->orderBy('created_at', 'asc')->get(); // Obtiene la última lectura
            }
            return $consumos;
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function busquedaPorId($id)
    {
        try {
            return Consumo::with([
                'toma',
                'lecturaAnterior',
                'lecturaActual',
                //'origen',
                'periodo'
            ])->find($id);
        } catch (Exception $ex) {
            throw $ex;
        }
    }

    public function registrarConsumo($data){
        try{
            $consumo = null;
            if($data){
                $data['id_toma'] = 0;
                $data['id_periodo'] = 0;
                $data['id_lectura_anterior'] = 0;
                $data['id_lectura_actual'] = 0;
                $data['tipo'] = 0;
                $data['estado'] = 0;
                $data['consumo'] = 0;
                $consumo = Consumo::create($data);
                if($consumo){} else{
                    throw new Exception("fallo al registrar la lectura");
                }
            }else{}
            return $consumo;
        }catch(Exception $ex){
            throw $ex;
        }
    }
}