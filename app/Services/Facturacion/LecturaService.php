<?php
namespace App\Services\Facturacion;

use App\Models\Lectura;
use Illuminate\Database\Eloquent\Collection;
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

    public function importarLecturas($lecturas)
    {
        try {
            $nuevasLecturas = new Collection();
            foreach ($lecturas as $lectura) {
                $toma = [
                    "id_operador" => $lectura['id_operador'],
                    "id_toma" => $lectura['id_toma'],
                    "id_periodo" => $lectura['id_periodo'],
                    "id_origen" => $lectura['id_origen'],
                    "modelo_origen" => $lectura['modelo_origen'],
                    "id_anomalia" => $lectura['id_anomalia'],
                    "lectura" => $lectura['lectura'],
                    "comentario" => $lectura['comentario']
                ];

                // Crear la nueva lectura y agregarla a la colección
                $nuevasLecturas->push(Lectura::create($toma));
            }
            return $nuevasLecturas;
        } catch (Exception $ex) {
            // Manejar cualquier excepción que ocurra durante el proceso
            throw $ex;
        }
    }

}