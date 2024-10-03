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
            // Iniciar la consulta base del modelo Lectura
            $query = Lectura::with([
                'operador',
                'toma',
                'periodo',
                'anomalia',
                // Si deseas agregar más relaciones, puedes descomentar otras líneas
            ]);

            // Filtros para los campos del modelo Lectura
            if (isset($data['id_operador'])) {
                $query->where('id_operador', $data['id_operador']);
            }

            if (isset($data['id_toma'])) {
                $query->where('id_toma', $data['id_toma']);
            }

            if (isset($data['id_periodo'])) {
                $query->where('id_periodo', $data['id_periodo']);
            }

            if (isset($data['id_anomalia'])) {
                $query->where('id_anomalia', $data['id_anomalia']);
            }

            if (isset($data['lectura_min']) && isset($data['lectura_max'])) {
                $query->whereBetween('lectura', [$data['lectura_min'], $data['lectura_max']]);
            } elseif (isset($data['lectura_min'])) {
                $query->where('lectura', '>=', $data['lectura_min']);
            } elseif (isset($data['lectura_max'])) {
                $query->where('lectura', '<=', $data['lectura_max']);
            }

            if (isset($data['comentario'])) {
                $query->where('comentario', 'like', '%' . $data['comentario'] . '%');
            }

            // Filtros para las relaciones (ejemplo con operador, toma y periodo)
            if (isset($data['nombre_operador'])) {
                $query->whereHas('operador', function ($q) use ($data) {
                    $q->where('nombre', 'like', '%' . $data['nombre_operador'] . '%');
                });
            }

            if (isset($data['codigo_toma'])) {
                $query->whereHas('toma', function ($q) use ($data) {
                    $q->where('codigo', 'like', '%' . $data['codigo_toma'] . '%');
                });
            }

            if (isset($data['nombre_periodo'])) {
                $query->whereHas('periodo', function ($q) use ($data) {
                    $q->where('nombre', 'like', '%' . $data['nombre_periodo'] . '%');
                });
            }

            if (isset($data['anomalia'])) {
                $query->whereHas('anomalia', function ($q) use ($data) {
                    $q->where('descripcion', 'like', '%' . $data['anomalia'] . '%');
                });
            }

            // Ordenar los resultados si es necesario
            $query->orderBy('created_at', 'asc');

            // Ejecutar la consulta
            $lecturas = $query->get();

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
                $data['id_operador'] = 0;
                $data['id_periodo'] = 0;
                $data['id_origen'] = 0;
                $data['modelo_origen'] = '';
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
                    "id_operador" => $lectura['id_operador'] ?? 0,
                    "id_toma" => $lectura['id_toma'] ?? 0,
                    "id_periodo" => $lectura['id_periodo'] ?? 0,
                    "id_origen" => $lectura['id_origen'] ?? 0,
                    "modelo_origen" => $lectura['modelo_origen'] ?? '',
                    "id_anomalia" => $lectura['id_anomalia'] ?? 0,
                    "lectura" => $lectura['lectura'] ?? 0,
                    "comentario" => $lectura['comentario'] ?? ''
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