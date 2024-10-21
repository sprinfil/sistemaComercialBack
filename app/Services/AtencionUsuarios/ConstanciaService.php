<?php

namespace App\Services\AtencionUsuarios;

use App\Models\Archivo;
use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\Constancia;
use App\Models\ConstanciaCatalogo;
use App\Models\TarifaConceptoDetalle;
use App\Models\Toma;
use App\Models\Usuario;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Exception;
use Illuminate\Support\Facades\Storage;

class ConstanciaService
{
    public function storeService(array $data)
    {
        try {
            $procesoPendiente = Constancia::where('modelo_dueno',$data['modelo_dueno'])
            ->where('id_dueno',$data['id_dueno'])
            ->where('id_catalogo_constancia',$data['id_catalogo_constancia'])
            ->whereIn('estado', ['pendiente', 'pagado'])
            ->get();

            if (count($procesoPendiente) == 0) {
                $monto = 0;
                $tipoConstancia = ConstanciaCatalogo::where('id',$data['id_catalogo_constancia'])
                ->where('estado','activo')
                ->first();

                $usuario = auth()->user();
                $idOperador = $usuario->operador->id;

                $modelo = Toma::where('id',$data['id_dueno'])
                            ->first();

                 $folio  = Constancia::darFolio();

                 $saldo = $modelo->saldoPendiente();
                 if($saldo != 0)
                 {
                     return response()->json([
                         'error' => 'La toma tiene saldo pendiente.'.$saldo
                     ], 500);
                 }

                if ($tipoConstancia) {
                    switch ($tipoConstancia->nombre) {

                        case "Constancia no adeudo":

                            break;
                        case "Constancia de antigüedad":

                            if ($modelo->fecha_instalacion == null) {
                                return response()->json([
                                    'error' => 'La toma seleccionada aun no ha sido instalada.'
                                ], 500);
                            }
                            break;

                        default:
                        return response()->json([
                            'error' => 'El tipo de constancia seleccionado esta desactivado o no existe.'
                        ], 500);
                            break;
                    }

                }

                $conNoAdeudo = [
                    "id_catalogo_constancia" => $data['id_catalogo_constancia'],
                    "estado" => "pendiente",
                    "id_operador" => $idOperador,
                    "id_dueno" => $data['id_dueno'],
                    "modelo_dueno" => $data['modelo_dueno'],
                    "folio_solicitud" => $folio,
                ];


                $constancia = Constancia::create($conNoAdeudo);

                $concepto = ConceptoCatalogo::find(150);//to do pendiente asignar un concepto default para constancia

                $monto = TarifaConceptoDetalle::select('monto')
                ->where('id_concepto',$concepto->id)
                ->where('id_tipo_toma',$modelo->id_tipo_toma)
                ->first();

                //cargo

                $fecha = helperFechaAhora();
                $fecha = Carbon::parse($fecha)->format('Y-m-d');

                $iva = round(($monto->monto * 16)/100,2);

                $RegistroCargo = [
                  "id_concepto" => $concepto->id,
                  "nombre" => $concepto->nombre,

                  "id_origen" => $constancia->id,
                  "modelo_origen" => 'constancia',

                  "id_dueno" => $data['id_dueno'],
                  "modelo_dueno" => $data['modelo_dueno'],

                  "monto" => $monto->monto,
                  "iva" => $iva,
                  "estado" => 'pendiente',
                  "id_convenio" => null,

                  "fecha_cargo" => $fecha,
                  "fecha_liquidacion" => null,

                ];
                $cargo = Cargo::create($RegistroCargo);
            }
            else{
                return response()->json([
                    'error' => 'La toma o usuario seleccionado tiene una constancia del mismo tipo en proceso.'
                ], 500);
            }

        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al registrar la solicitud de constancia.'. $ex
            ], 500);
        }
    }

    public function pagoConstanciaService(int $id_constancia)
    {
        try {
            $fecha = helperFechaAhora();
            $fechaFormato = Carbon::parse($fecha)->format('d/m/Y');
            $año = Carbon::parse($fecha)->format('Y');

            $constancia = Constancia::where('id',$id_constancia)
            ->first();
            $constancia->update(['estado' => 'pagado']);

            $conCatalogo = ConstanciaCatalogo::select('nombre')
            ->where('id',$constancia->id_catalogo_constancia)
            ->first();

            $toma = Toma::where('id',$constancia->id_dueno)
            ->first();

            $usuario = Usuario::where('id',$toma->id_usuario)
            ->first();
            //Aqui va la parte de generar las constancias

           $texto = Carbon::parse($fecha)->translatedFormat('l, j \d\e F \d\e Y');
           $fechaInstalacion =  Carbon::parse($toma->fecha_instalacion)->format('d/m/Y');

           switch ($conCatalogo->nombre) {

            case "Constancia no adeudo":

                $data = [
                    'folio' => $constancia->folio_solicitud,
                    'codigo_usuario' => $usuario->codigo_usuario,
                    'nombre' => $usuario->getNombreCompletoAttribute(),
                    'domicilio' => $toma->getDireccionCompleta(),
                    'facturacion_previa' => "pendiente",//una cosa a la vez ´pa
                    'fecha_texto' => $texto,
                    'fecha'=>$fechaFormato,
                    'nombre_sistema'=> "Sistema municipal de agua potable",
                    'año' => $año,
                ];

                $pdf = FacadePDF::loadView('constanciaNoAdeudo', $data)
                ->setPaper('A4', 'portrait') // Tamaño de papel y orientación
                ->setOption('margin-top', 0)
                ->setOption('margin-right', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0);

                // Define la ruta donde se guardará el PDF en el almacenamiento público
                $path = storage_path('app/public/documentos/constancias');

                // Asegúrate de que el directorio existe
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $filename = 'constancia_no_adeudo_' . strtolower(str_replace(' ', '_', $usuario->getNombreCompletoAttribute())) . '_' . now()->format('Ymd') . '.pdf';
                $pdf->save($path . '/' . $filename);

                break;

            case "Constancia de antigüedad": //tipo toma domestico?, clave catastral

                $data = [
                    'folio' => $constancia->folio_solicitud, //
                    'codigo_usuario' => $usuario->codigo_usuario, //
                    'nombre' => $usuario->getNombreCompletoAttribute(), //
                    'domicilio' => $toma->getDireccionCompleta(), //
                    'fecha_texto' => $texto, //
                    'fecha_instalacion'=>$fechaInstalacion, //
                    'tipo_toma' => $toma->tipoToma->nombre,
                    'clave_catastral' => $toma->clave_catastral,
                    'nombre_sistema'=> "Sistema municipal de agua potable",
                    'año' => $año,
                ];

                $pdf = FacadePDF::loadView('constanciaAntiguedad', $data)
                ->setPaper('A4', 'portrait') // Tamaño de papel y orientación
                ->setOption('margin-top', 0)
                ->setOption('margin-right', 0)
                ->setOption('margin-bottom', 0)
                ->setOption('margin-left', 0);

                // Define la ruta donde se guardará el PDF en el almacenamiento público
                $path = storage_path('app/public/documentos/constancias');

                // Asegúrate de que el directorio existe
                if (!file_exists($path)) {
                    mkdir($path, 0755, true);
                }

                $filename = 'constancia_antiguedad_' . strtolower(str_replace(' ', '_', $usuario->getNombreCompletoAttribute())) . '_' . now()->format('Ymd') . '.pdf';
                $pdf->save($path . '/' . $filename);

                break;

            default:
            return response()->json([
                'error' => 'El tipo de constancia seleccionado esta desactivado o no existe.'
            ], 500);
                break;
        }

           $repeArchivo = Archivo::select('url')->where('url',$filename)->first();
            if ($repeArchivo == null) {
                $archivo = [
                    'id_modelo' => $id_constancia,
                    'modelo' => "constancia",
                    'url' => $filename,  // Guardar solo el nombre del archivo
                    'tipo' => 'PDF',
                ];
                $archivo = Archivo::create($archivo);
                //return $pdf->download('constanciaNoAdeudo.pdf'); esto es para pruebas
            }
            else {
                //return $this->buscarConstanciaService($repeArchivo->url); esto es para pruebas
            }


        } catch (Exception $ex) {
             return response()->json([
                'error' => 'Ocurrio un error al procesar el pago de la constancia.'. $ex
            ], 500);
        }
    }

    public function buscarConstanciaService( $filename)
    {
        try {
             // Ruta relativa dentro del disco 'public'
        $filePath = 'documentos/constancias/' . $filename;

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
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al buscar la constancia.'. $ex
            ], 500);
        }
    }

    public function buscarRegistroConstanciaService(array $data)
    {
        try {
            $constanciasPenEntregar = Constancia::where('modelo_dueno',$data['modelo_dueno'])
            ->where('id_dueno',$data['id_dueno'])
            ->whereIn('estado', ['pagado', 'entregado'])
            ->with('archivo')
            ->get();
            if ($constanciasPenEntregar) {
                return $constanciasPenEntregar;
            }
            else {
                return response()->json([
                    'error' => 'Ocurrio un error al buscar la constancia.'
                ], 500);
            }

        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al buscar la constancia.'. $ex
            ], 500);
        }
    }

    public function EntregarConstanciaService(int $data)
    {
        try {
            //$data = 15;
            //return $data;
            if ($data) {
                Constancia::where('id', $data)->update(['estado' => 'entregado']);
                $archivo = Archivo::select('url')->where('id_modelo',$data)->first();
                return $this->buscarConstanciaService($archivo->url);

            }
            else {
                return response()->json([
                    'error' => 'Debe enviar un id valido para realizar la enttrega de la constancia.'
                ], 500);
            }

        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al entregar la constancia.'. $ex
            ], 500);
        }
    }

    public function buscarTodasConstanciasService(array $data)
    {
        try {
            $constanciasPenEntregar = Constancia::where('modelo_dueno',$data['modelo_dueno'])
            ->where('id_dueno',$data['id_dueno'])
            ->orderby("id", "desc")
            ->get();
            if ($constanciasPenEntregar) {
               return json_encode($constanciasPenEntregar);
            }
            else {
                return response()->json([
                    'error' => 'No se encontraron constancias asociadas a esta toma.'
                ], 500);
            }
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al buscar las constancias'. $ex
            ], 500);
        }
    }

    public function cancelarConstanciaService(array $data)
    {
        //->update(['estado' => 'cancelado'])
        try {
            $constancia = Constancia::where('id',$data['id_constancia'])
            ->first();
            if ($constancia->estado == "pendiente") {
                $constancia->update(['estado' => 'cancelado']);
               return json_encode($constancia);
            }
            else {
                return response()->json([
                    'error' => 'No se pueden cancelar constancias pagadas o entregadas'
                ], 500);
            }

        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al cancelar la constancia'. $ex
            ], 500);
        }
    }
}
