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
            // Iniciar la consulta base del modelo Consumo
            $query = Consumo::with([
                'toma',
                'lecturaAnterior',
                'lecturaActual',
                'periodo'
            ]);
    
            // Filtros para los campos del modelo Consumo
            if (isset($data['id_toma'])) {
                $query->where('id_toma', $data['id_toma']);
            }
    
            if (isset($data['id_periodo'])) {
                $query->where('id_periodo', $data['id_periodo']);
            }
    
            if (isset($data['estado'])) {
                $query->where('estado', $data['estado']);
            }
    
            if (isset($data['tipo'])) {
                $query->where('tipo', $data['tipo']);
            }
    
            if (isset($data['consumo_min']) && isset($data['consumo_max'])) {
                $query->whereBetween('consumo', [$data['consumo_min'], $data['consumo_max']]);
            } elseif (isset($data['consumo_min'])) {
                $query->where('consumo', '>=', $data['consumo_min']);
            } elseif (isset($data['consumo_max'])) {
                $query->where('consumo', '<=', $data['consumo_max']);
            }
    
            // Filtros para las relaciones
            if (isset($data['fecha_lectura_actual'])) {
                $query->whereHas('lecturaActual', function ($q) use ($data) {
                    $q->whereDate('fecha', $data['fecha_lectura_actual']);
                });
            }
    
            if (isset($data['fecha_lectura_anterior'])) {
                $query->whereHas('lecturaAnterior', function ($q) use ($data) {
                    $q->whereDate('fecha', $data['fecha_lectura_anterior']);
                });
            }
    
            // Ordenar los resultados si es necesario
            $query->orderBy('created_at', 'asc');
    
            // Ejecutar la consulta
            $consumos = $query->get();
    
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