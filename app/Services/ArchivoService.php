<?php

namespace App\Services;

use App\Http\Requests\StoreArchivoRequest;
use App\Models\Archivo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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
        
                    // Obtener solo el nombre del archivo (sin la ruta completa)
                    $filename = basename($path);
        
                    // Determinar el tipo de archivo según la extensión
                    $extension = $file->getClientOriginalExtension();
                    $tipoArchivo = $this->determinarTipoArchivo($extension);
        
                    // Agregar la información del archivo al array, guardando solo el nombre del archivo
                    $archivo = [
                        'modelo' => $data['modelo'],
                        'id_modelo' => $data['id_modelo'],
                        'url' => $filename,  // Guardar solo el nombre del archivo
                        'tipo' => $tipoArchivo,
                    ];
        
                    // Crear el registro en la base de datos
                    $archivo = Archivo::create($archivo);
                }
            }
        
            return $archivo;
        } catch (Exception $e) {
            return $e;
        }
    }

    public function bajar($filename)
    {
        // Ruta relativa dentro del disco 'public'
        $filePath = 'documentos/' . $filename;

        // Verificar si el archivo existe en el disco 'public'
        if (Storage::disk('public')->exists($filePath)) {
            // Obtener el contenido del archivo
            $fileContent = Storage::disk('public')->get($filePath);
            $fileName = basename($filePath);

            // Obtener el tipo MIME del archivo
            $mimeType = Storage::disk('public')->mimeType($filePath);

            // Devolver el archivo como respuesta para descarga
            return response($fileContent)
                ->header('Content-Type', $mimeType)
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
        } else {
            // Archivo no encontrado
            return response()->json(['error' => 'Archivo no encontrado'], 404);
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