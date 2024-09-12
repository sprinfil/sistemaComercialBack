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
            if ($request->hasFile('documento')) {
                $file = $request->file('documento');
                $path = $file->store('documentos', 'public'); // Guardar en el almacenamiento público

                // Agregar la ruta del archivo al campo correspondiente
                $data['url'] = $path;
            }
            $archivo = Archivo::create($data);
            return $archivo;
        } catch (Exception $e) {
            return $e;
        }
    }
}
