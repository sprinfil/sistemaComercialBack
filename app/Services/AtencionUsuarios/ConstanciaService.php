<?php

namespace App\Services\AtencionUsuarios;

use App\Models\Cargo;
use App\Models\ConceptoCatalogo;
use App\Models\Constancia;
use App\Models\ConstanciaCatalogo;
use App\Models\TarifaConceptoDetalle;
use App\Models\Toma;
use Carbon\Carbon;
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
                            
                            $saldo = $modelo->saldoPendiente;
                            if($saldo == 0)
                            {
                                $conNoAdeudo = [
                                    "id_catalogo_constancia" => $data['id_catalogo_constancia'],
                                    "estado" => "pendiente",
                                    "id_operador" => $idOperador,
                                    "id_dueno" => $data['id_dueno'],
                                    "modelo_dueno" => $data['modelo_dueno'],
                                ];
                                $constancia = Constancia::create($conNoAdeudo);

                                $concepto = ConceptoCatalogo::find(148);//to do pendiente asignar un concepto default para constancia
                                
                                $monto = TarifaConceptoDetalle::select('monto')
                                ->where('id_concepto',$concepto->id)
                                ->where('id_tipo_toma',$modelo->id_tipo_toma)
                                ->first();
                            }
                            break;
                    
                        case "Constancia de contratacion reciente"://nel
                            // Código a ejecutar si la expresión es igual a valor2
                            break;
                    
                        // Puedes tener tantos casos como desees
                        case "Constancia de antigüedad":

                            $conAntigu = [
                                "id_catalogo_constancia" => $data['id_catalogo_constancia'],
                                "estado" => "pendiente",
                                "id_operador" => $idOperador,
                                "id_dueno" => $data['id_dueno'],
                                "modelo_dueno" => $data['modelo_dueno'],
                            ];
                            $constancia = Constancia::create($conAntigu);

                            $concepto = ConceptoCatalogo::find(148);//to do pendiente asignar un concepto default para constancia
                            
                            $monto = TarifaConceptoDetalle::select('monto')
                            ->where('id_concepto',$concepto->id)
                            ->where('id_tipo_toma',$modelo->id_tipo_toma)
                            ->first();
                            break;

                         case "Constancia de no servicio"://nel
                            // Código a ejecutar si la expresión es igual a valor2
                            break;
                    
                    
                        default:
                            // Código a ejecutar si la expresión no coincide con ninguno de los casos
                            break;
                    }

                }
                //cargo
             
                $fecha = helperFechaAhora();
                $fecha = Carbon::parse($fecha)->format('Y-m-d');

                $iva = ($monto * 16)/100;

          
                $RegistroCargo = [
                  "id_concepto" => $concepto->id,
                  "nombre" => $concepto->nombre,
          
                  "id_origen" => $constancia->id,
                  "modelo_origen" => 'constancia',
          
                  "id_dueno" => $data['id_dueno'],
                  "modelo_dueno" => $data['modelo_dueno'],
          
                  "monto" => $monto,
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
                    'error' => 'La toma o usuario seleccionado tiene una constancia del mismo tipo en proceso'
                ], 500);
            }

        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al registrar la solicitud de constancia'. $ex
            ], 500);
        }
    }
}
