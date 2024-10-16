<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreArchivoRequest;
use App\Http\Resources\ArchivoResource;
use App\Models\Factibilidad;
use App\Http\Requests\StoreFactibilidadRequest;
use App\Http\Requests\UpdateFactibilidadRequest;
use App\Http\Resources\FactibilidadResource;
use App\Models\Archivo;
use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\Contrato;
use App\Services\ArchivoService;
use Exception;
use Illuminate\Auth\Events\Validated;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use ErrorException;

class FactibilidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            return response(FactibilidadResource::collection(
                Factibilidad::all()
            ), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No fue posible consultar una factibilidad' . $e
            ], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Factibilidad $factibilidad, StoreFactibilidadRequest $request) //
    {
        
        
        try {
            $data = $request->validated();
            $data['estado'] = 'sin revisar';
            $data['servicio'] = 'agua';
            $data['estado_servicio'] = 'pendiente';
            //$data['san_estado_factible'] = 'pendiente';
            $factibilidad = Factibilidad::create($data);
            return response(new FactibilidadResource($factibilidad), 201);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la factibilidad' . $e
            ], 500);
        }
            
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $factibilidad = Factibilidad::findOrFail($id);
            // Cargar la relación 'archivos'
            $factibilidad->load('archivos', 'toma');
            return response(new FactibilidadResource($factibilidad), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo encontrar la factibilidad'
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateFactibilidadRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $factibilidad = Factibilidad::findOrFail($id);

            // if ($request->hasFile('documento')) {
            //     $file = $request->file('documento');
            //     $path = $file->store('documentos', 'public'); // Guardar en el almacenamiento público

            //     // Agregar la ruta del archivo al campo correspondiente
            //     $data['documento'] = $path;
            // }
            
            // Agregar id de contrato
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
                        'modelo' => 'factibilidad',
                        'id_modelo' => $id,
                        'url' => $filename,  // Guardar solo el nombre del archivo
                        'tipo' => $tipoArchivo,
                    ];
        
                    // Crear el registro en la base de datos
                    $archivo = Archivo::create($archivo);
                }
            }

            $factibilidad->update($data);
            $factibilidad->save();
            $contrato=$factibilidad->contrato;
            if ($factibilidad->estado_servicio=="factible"){
                $contrato->estatus="inspeccionado";
            }
            else if ($factibilidad->estado_servicio=="no factible"){
                $contrato->estatus="contrato no factible";
            }
            $contrato->save();
         
            $factibilidad_cargada = Factibilidad::findOrFail($factibilidad->id);
            if ($factibilidad_cargada->servicio=="agua"){
                $concepto = ConceptoCatalogo::findOrFail(43);
            }
            else{
                $concepto = ConceptoCatalogo::findOrFail(44);
            }
          
            $RegistroCargo = [
                "id_concepto" => $concepto->id,
                "nombre" => $concepto->nombre,
      
                "id_origen" => $factibilidad->id,
                "modelo_origen" => 'factibilidad',
      
                "id_dueno" => $factibilidad->id_toma,
                "modelo_dueno" => 'toma',
      
                "monto" => $factibilidad->derechos_conexion ?? 0,
                "iva" => $factibilidad->derechos_conexion*0.12,
                "estado" => 'pendiente',
                "id_convenio" => null,
      
                "fecha_cargo" => now(),
                "fecha_liquidacion" => null,
      
            ];
            $cargo = Cargo::create($RegistroCargo);

            $factibilidad->load('archivos', 'toma');

            return response(new FactibilidadResource($factibilidad), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo editar la factibilidad'.$e
            ], 500);
        }
    }

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

    /**
     * Remove the specified resource from storage.
     */
    /*public function destroy(Factibilidad $factibilidad, $id)
    {
        try {
            $factibilidad = Factibilidad::findOrFail($id);
            $factibilidad->delete();
            return response("Factibilidad eliminada",200);
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'error' => 'No se pudo borrar la factibilidad'
            ], 500);
        }
    }*/

    /*public function restaurar (Factibilidad $factibilidad, Request $request)
    {
        try {
            $factibilidad = Factibilidad::withTrashed()->findOrFail($request->id);
            //Condicion para verificar si el registro esta eliminado
            if ($factibilidad->trashed()) {
                //Restaura el registro
                $factibilidad->restore();
                return response()->json(['message' => 'La factibilidad ha sido restaurada con exito' , 200]);
            }
        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Hubo un error al restaurar la factibilidad'
            ]);
        }
    }*/

    //$factura = (new FacturaService())->storeFacturaService($data);

    public function generarConstancia($id)
    {
        try {
            $factibilidad = Factibilidad::findOrFail($id);
            $calle1 = $factibilidad->toma->calle1->nombre ?? '';
            $calle2 = $factibilidad->toma->entre_calle1->nombre ?? '';
            $calle3 = $factibilidad->toma->entre_calle2->nombre ?? '';
            $calle4 = $factibilidad->toma->getDireccionCompleta() ?? '';
            $data = [
                'factibilidad' => $factibilidad->id,
                'calle' =>  $calle1 ?? '',
                'numero_casa' => $factibilidad->toma->numero_casa,
                'servicio' => strtoupper($factibilidad->servicio),
                'estado_servicio' => strtoupper($factibilidad->estado_servicio),
                'calle_entre' => $calle2 ?? '',
                'calle_y' => $calle3 ?? '',
                'costo_factibilidad' => $factibilidad->derechos_conexion,
                'toma' => $factibilidad->toma->codigo_toma,
                'notificacion_calle' => $calle4 ?? '',
                'nombre_solicitante' => $factibilidad->toma->usuario->getNombreCompletoAttribute(),
                'nombre_sistema' => 'Sistema Municipal',
            ];
            $pdf = FacadePDF::loadView('factibilidad', $data) // Nombre de la vista
                ->setPaper('A4', 'portrait')  // Tamaño de papel y orientación vertical
                ->setOption('margin-top', 0)
                ->setOption('margin-right', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0);

            return $pdf->download('constancia_factibilidad.pdf');  // Descarga directa del PDF
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo obtener la constancia' . $ex
            ], 500);
        }
    }

    public function storeFile(StoreArchivoRequest $request, $id)
    {
        try {
            return response()->json(new ArchivoResource((new ArchivoService())->subir($request)), 200);
        } catch (Exception $e) {
            return response()->json([
                'error' => 'No se pudo guardar la factibilidad' . $e
            ], 500);
        }
    }
}
