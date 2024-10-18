<?php

namespace App\Services\AtencionUsuarios;

use App\Http\Resources\DescuentoAsociadoResource;
use App\Models\Abono;
use App\Models\Archivo;
use App\Models\Cargo;
use App\Models\Descuento;
use App\Models\DescuentoAsociado;
use App\Models\DescuentoCatalogo;
use App\Models\Toma;
use Exception;
use Illuminate\Http\Request;
use Symfony\Component\Console\Input\Input;

class DescuentoAsociadoService
{

    public function index()
    {
        try {
            return response(DescuentoAsociadoResource::collection(
                DescuentoAsociado::all()
            ), 200);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Ocurrio un error al buscar los descuentos asociados'], 500);
        }
    }

    public function store(array $data)
    {

            $folio = $data['folio'];
            $id_modelo = $data['id_modelo'];
            $modelo_dueno = $data['modelo_dueno'];
            $curp = $data['curp'];

            //Si el id_modelo / modelo dueño existen en descuentos asociados
            $dueno = DescuentoAsociado::where('id_modelo', $id_modelo)
                ->where('modelo_dueno', $modelo_dueno)
                ->where('estatus', 'vigente')
                ->exists();
            $folio_igual = DescuentoAsociado::where('folio', $folio)->exists();
            $curp_igual = DescuentoAsociado::where('curp', $curp)->exists();
            if ($dueno) {
                 throw new \Exception('ya tiene un Descuento activo.', 400);
                //return response()->json(['message' => 'Ya tiene un Descuento activo'], 400);
            }
            if ($folio_igual) {
                 throw new \Exception('el folio no esta disponible.', 400);
                //return response()->json(['message' => 'El folio no esta disponible'], 400);
            }
            $descuento = DescuentoAsociado::create($data);
            $descuento->load('descuento_catalogo' , 'archivos');
            return $descuento;
    }

    public function filtro($id_modelo, $modelo_dueno)
    {
        try {
            $filtro = DescuentoAsociado::with('descuento_catalogo' , 'archivos')
                ->when($id_modelo, function ($query, $id_modelo) {
                    return $query->where('id_modelo', $id_modelo);
                })
                ->when($modelo_dueno, function ($query, $modelo_dueno) {
                    return $query->where('modelo_dueno', $modelo_dueno);
                })
                ->orderBy('id', 'desc')
                ->get();
            if ($filtro->isEmpty()) {
                return response()->json(['message' => 'No se encontraron resultados'], 404);
            } else {
                return DescuentoAsociadoResource::collection($filtro);
            }
        } catch (Exception $ex) {
            return response()->json(['error' => 'Ocurrio un error al consultar el descuento asociado. ' . $ex], 500);
        }
    }

    public function CancelarDescuento(array $data, $id)
    {
        try {
            $status = $data['estatus'];
            $estatus = DescuentoAsociado::findOrFail($id);
            if (!$estatus) {
                return response()->json(['message' => 'No se encontraron resultados. ', 404]);
            }
            $estatus->update(['estatus' => $status]);
            $estatus->save();
            $estatus->load('descuento_catalogo');
            return response(new DescuentoAsociadoResource($estatus), 200);
        } catch (Exception $ex) {
            return response()->json(['error' => 'Ocurrio un error al cancelar el descuento. ' . $ex], 500);
        }
    }

    public function guardarArchivo($files, $descuentoAsociado)
    {
        $path = $files->store('evidencia', 'public');
        $filename = basename($path);
        $extension = $files->getClientOriginalExtension();
        $tipoArchivo = $this->determinarTipoArchivo($extension);
        $descuentoAsociado->archivos()->create([
            'url' => $filename,
            'tipo' => $tipoArchivo
        ]);
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

    //Alan
    public function facturarCndescuento(int $id_toma, int $id_facturacion)
    {
        try {

            $toma = Toma::where('id',$id_toma)->first();

            if ($toma) {

                $descuentoAsociado = $toma->descuentoAsociado->where('estatus','vigente')->first();
                $descuentoCatalogo = $descuentoAsociado->descuento_catalogo;
                $cargos = Cargo::where('modelo_origen','facturacion')
                ->where('id_origen',$id_facturacion)
                ->where('estado','pendiente')
                ->get();

                foreach ($cargos as $cargo) {

                    $conceptoAplicable = $descuentoCatalogo->conceptosAplicables->where('id_concepto_catalogo',$cargo->id_concepto)->first();
                    $conceptoAplicable->rango_maximo;
                    if ($conceptoAplicable) {
                        $descuentoMonto = round((($cargo->monto + $cargo->iva) * $conceptoAplicable->rango_maximo)/100 , 2);
                        $descuento = [
                            "id_descuento_aplicado" => $descuentoCatalogo->id,
                            "id_descuento_asociado" => $descuentoAsociado->id,
                            "monto_total" => $descuentoMonto,
                            "estado" => "abonado",
                        ];
                        $descuentoReg = Descuento::create($descuento);

                        $abono = [
                            "id_cargo" => $cargo->id,
                            "id_origen" => $descuentoReg->id,
                            "modelo_origen" => "descuento",
                            "total_abonado" => $descuentoReg->monto_total,
                        ];

                        $abonoReg = Abono::create($abono);
                    }
                }
            }
           
        } catch (Exception $ex) {
            return response()->json(['error' => 'Ocurrio un error al aplicar el descuento. ' . $ex], 500);
        }
    }

    //Alan 
    public function cancelarDecuento(int $id_toma, int $id_facturacion)
    {
        try {
            $toma = Toma::where('id',$id_toma)->first();
            if ($toma) 
            {
                $cargos = Cargo::where('modelo_origen','facturacion')
                ->where('id_origen',$id_facturacion)
                ->get();
                
                foreach ($cargos as $cargo) {
                    $abono = Abono::select('id_origen')->where('modelo_origen','descuento')->where('id_cargo',$cargo->id)->get()->toArray();
                    $idOrigenes = array_column($abono, 'id_origen');
                    Descuento::whereIn('id',$idOrigenes)->update(['estado' => 'cancelado']);
                }
            }
        } catch (Exception $ex) {
            return response()->json(['error' => 'Ocurrio un error al cancelar el descuento. ' . $ex], 500);
        }
    }
}
