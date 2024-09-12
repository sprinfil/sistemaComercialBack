<?php

namespace App\Services;

use App\Http\Requests\StoreArchivoRequest;
use App\Models\Archivo;
use Exception;
use Illuminate\Http\Request;

class ArchivoService
{
    public function subir(StoreArchivoRequest $request)
    {
        try {
            $data = $request->all();

            // Crear un array para almacenar las rutas de los archivos y sus tipos
            $archivos = [];

            if ($request->hasFile('documentos')) {
                foreach ($request->file('documentos') as $file) {
                    // Guardar el archivo en el almacenamiento público
                    $path = $file->store('documentos', 'public');

                    // Determinar el tipo de archivo según la extensión
                    $extension = $file->getClientOriginalExtension();
                    $tipoArchivo = $this->determinarTipoArchivo($extension);

                    // Agregar la información del archivo al array
                    $archivo = [
                        'modelo' => $data['modelo'],
                        'id_modelo' => $data['id_modelo'],
                        'url' => $path,
                        'tipo' => $tipoArchivo,
                    ];

                    $archivo = Archivo::create($archivo);
                }
            }

            // Crear el registro principal en la base de datos con los datos del modelo
            //$archivo = Archivo::create($data);

            // Asociar los archivos subidos al modelo principal (si es una relación)
            // foreach ($archivos as $archivoInfo) {
            //     $archivo->documentos()->create($archivoInfo);
            // }

            return $archivo;
        } catch (Exception $e) {
            return $e;
        }
    }

    // Método para determinar el tipo de archivo según la extensión
    private function determinarTipoArchivo($extension)
    {
        switch (strtolower($extension)) {
            case 'pdf':
                return 'PDF';
            case 'jpg':
            case 'jpeg':
            case 'png':
                return 'Imagen';
            case 'doc':
            case 'docx':
                return 'Documento de Word';
            case 'xls':
            case 'xlsx':
                return 'Hoja de cálculo';
            default:
                return 'Desconocido';
        }
    }   
}