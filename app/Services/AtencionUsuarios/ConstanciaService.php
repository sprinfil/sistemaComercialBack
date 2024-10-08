<?php

namespace App\Services\AtencionUsuarios;

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
                if ($tipoConstancia) {
                    switch ($tipoConstancia->nombre) {

                        case "Constancia no adeudo":
                            
                            $saldo = $modelo->saldoPendiente();
                            if($saldo != 0)
                            {
                                return response()->json([
                                    'error' => 'La toma tiene saldo pendiente.'.$saldo
                                ], 500);
                            }
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

    public function pagoConstanciaService(array $data)
    {
        try {
            $fecha = helperFechaAhora();
            $fechaFormato = Carbon::parse($fecha)->format('d/m/Y');

            $constancia = Constancia::where('id',$data['id_constancia'])
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

            $data = [
                'codigo_usuario' => $usuario->codigo_usuario,
                'nombre' => $usuario->getNombreCompletoAttribute(),
                'domicilio' => $toma->getDireccionCompleta(),
                'facturacion_previa' => "pendiente",//una cosa a la vez ´pa
                'fecha_texto' => $texto,
                'fecha'=>$fechaFormato,
                'nombre_sistema'=> "Sistema municipal de agua potable",
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

             $archivo = [
                'modelo' => $constancia->id_dueno,
                'id_modelo' => $constancia->modelo_dueno,
                'url' => $filename,  // Guardar solo el nombre del archivo
                'tipo' => 'PDF',
            ];
            return $pdf->download('constanciaNoAdeudo.pdf');

        } catch (Exception $ex) {
             return response()->json([
                'error' => 'Ocurrio un error al procesar el pago de la constancia.'. $ex
            ], 500);
        }
    }
}
