<?php
namespace App\Services\AtencionUsuarios;

use App\Http\Resources\MultaResource;
use App\Models\Cargo;
use App\Models\Multa;
use App\Models\MultaCatalogo;
use App\Models\Toma;
use App\Models\Usuario;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class MultaService{

    public function index ()
    {
        return response(MultaResource::collection(
            Multa::orderby('id', 'desc')->get()
        ), 200);
    }

    public function store ($data,$codigo_toma)
    {
        try {
            //Para levantar una multa, se necesita buscar el codigo de la toma. 
            //$codigo_toma = $data['codigo_toma'];
            $usuario = auth()->user();
            $data['id_operador'] = $usuario->operador->id;
            $cod_toma = Toma::where('codigo_toma' , $codigo_toma)
            ->first();
            $catalogo_multa = MultaCatalogo::where('id', $data['id_catalogo_multa'])
            ->where('estatus' , 'activo')
            ->first();
            //Cuenta cuantas multas tiene registrada la toma. 
            //$total_multas = Multa::where('id_multado', $cod_toma->id)->count();
            if (!$catalogo_multa) {
                return response()->json([
                    'message' => 'No se encontro la multa en el catalogo o la multa esta inactiva.'
                ], 404);
            }
            /*
            $monto = $data['monto'];
            if ($monto < $catalogo_multa->UMAS_min || $monto > $catalogo_multa->UMAS_max) {
                return response()->json([
                    'message' => 'El monto ingresado está fuera del rango (' . $catalogo_multa->UMAS_min . ' - ' . $catalogo_multa->UMAS_max . ').'
            ], 422);
            }
            */
            if ($cod_toma) {
                $data['id_multado'] = $cod_toma->id;
                $data['estado'] = 'pendiente';
                $data['fecha_solicitud'] = Carbon::now()->format('Y-m-d');
                $multa = Multa::create($data);
                return new MultaResource($multa);
            }
            else{
                return response()->json([
                    'message' => 'No se encontro la toma.'
                ], 404);
            }

        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al levantar la multa. ' .$ex->getMessage()
            ],500);
        }
    }

    public function show ($id)
    {
        try {
            $multa = Multa::findOrFail($id);
            return response(new MultaResource($multa), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'No se pudo encontrar la multa' .$ex->getMessage()
            ], 500);
        }
    }

    public function consultarmulta ($modelo_multado , $id_multado, $id_catalogo_multa, $codigo_usuario, $codigo_toma , $fecha_solicitud, $tipo_toma, $tipo_multa, $fecha_revision)
    {
        try {
            $filtro = Multa::with(['origen' , 'catalogo_multa' , 'origen.usuario' , 'origen.tipoToma'])
                ->when($id_multado, function ($query, $id_multado) {
                    return $query->where('id_multado', $id_multado);
                })
                ->when($modelo_multado, function ($query, $modelo_multado) {
                    return $query->where('modelo_multado', $modelo_multado);
                })
                ->when($id_catalogo_multa, function ($query, $id_catalogo_multa){
                    return $query->where('id_catalogo_multa' , $id_catalogo_multa);
                })
                ->when($fecha_solicitud, function ($query, $fecha_solicitud){
                    return $query->where('fecha_solicitud' , $fecha_solicitud);
                })
                ->when($fecha_revision, function ($query, $fecha_revision){
                    return $query->where('fecha_revision' , $fecha_revision);
                })
                ->when($codigo_toma, function ($query) use ($codigo_toma){
                    return $query->whereHas('origen' , function($query) use ($codigo_toma){
                        $query->where('codigo_toma' , $codigo_toma);
                    });
                })
                ->when($codigo_usuario, function ($query) use ($codigo_usuario){
                    return $query->whereHas('origen.usuario' , function($query) use ($codigo_usuario){
                        $query->where('codigo_usuario' , $codigo_usuario);
                    });
                })
                ->when($tipo_toma, function ($query) use ($tipo_toma){
                    return $query->whereHas('origen.tipoToma' , function($query) use ($tipo_toma){
                        $query->where('nombre' , $tipo_toma);
                    });
                })
                ->when($tipo_multa, function ($query) use ($tipo_multa){
                    return $query->whereHas('catalogo_multa' , function($query) use ($tipo_multa){
                        $query->where('nombre' , $tipo_multa);
                    });
                })
                ->orderBy('id' , 'desc')
                ->get();
           if ($filtro->isEmpty()) {
                return response()->json([
                    'message' => 'No se encontraron resultados. '
            ], 404);
           }
        return response(MultaResource::collection($filtro), 200);

        } catch (ModelNotFoundException $ex) {
            return response()->json(['error' => 'Ocurrio un error al consultar la multa del usuario / toma' . $ex] , 500);
        }
    }

    //Monitores de las multas
    public function monitordemultas ()
    {
        try {
            return response(MultaResource::collection(
                Multa::with('origen' , 'catalogo_multa' , 'operador_revisor')
                ->orderby('id', 'desc')->get()
            ), 200);
        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al mostrar las multas. '
            ], 500);
        }

    }

    public function modificarmulta (array $data , $id)
    {
        try {
            //Se busca la multa seleccionada del monitor.
            $multas = Multa::find($id);
            if (!$multas) {
               return response()->json([
                'message' => 'No se encontro la multa. '
               ], 400);
            }
            if ($multas->estado == 'activo') {
                return response()->json([
                    'message' => 'La multa ya esta activada, no se puede cambiar el estado.'
                ], 400);
            }
            if ($multas->estado == 'cancelado') {
                return response()->json([
                    'message' => 'La multa esta cancelada, no se puede cambiar el estado.'
                ], 400);
            }
            $catalogo_multa = MultaCatalogo::where('id' , $multas->id_catalogo_multa)
            ->where('estatus' , 'activo')->first();
            if (!$catalogo_multa) {
                return response()->json([
                    'message' => 'La multa seleccionada del catalogo de multas esta inactiva. '
                ], 404);
            }
            $umas = $data['monto'];
            if ($umas < $catalogo_multa->UMAS_min || $umas > $catalogo_multa->UMAS_max) {
                return response()->json([
                    'message' => 'La cantidad de UMAS debe estar entre ' . $catalogo_multa->UMAS_min . ' y ' . $catalogo_multa->UMAS_max
                ], 422);
            }
            $multas->estado = 'activo';
            //Generar el cargo
            $multas->fecha_revision = Carbon::now()->format('Y-m-d');
            $multas->monto = $umas;
            //$multas->update($data);

            //Al activar la multa, va a generar un cargo.
            $cargoiva = 0; //No generan iva las multas.
            $cargomulta = Cargo::create([
                'id_concepto' => 14,
                'nombre' => 'Cargo por multa',
                'id_origen' => $multas->id,
                'modelo_origen' => 'Multa',
                'id_dueno' => $multas->id_multado,
                'modelo_dueno' => $multas->modelo_multado,
                'monto' => $multas->monto,
                'iva' => $cargoiva,
                'estado' => 'pendiente',
                'fecha_cargo' => now(),
                'fecha_liquidacion' => null,

            ]);
            $multas->save();
            return response()->json([
                'message' => 'La multa ha sido activada. ',
                'cargo' => $cargomulta,
                'multa' => new MultaResource($multas),
            ] , 200);
            //return response(new MultaResource($multas), 200);

        } catch (Exception $ex) {
            return response()->json([
                'error' => 'Ocurrio un error al modificar la multa. ' .$ex->getMessage()
            ], 500);
        }
    }
    public function cancelarmulta ($id)
    {
        $multas = Multa::find($id);
        if (!$multas) {
           return response()->json([
            'message' => 'No se encontro la multa. '
           ], 400);
        }
        if ($multas->estado == 'cancelado') {
            return response()->json([
                'message' => 'La multa ya ha sido cancelada anteriormente.'
            ], 403);
        }
        if ($multas->estado != 'pendiente') {
            return response()->json([
                'message' => 'No se puede cancelar la multa una vez activa o cancelada.'
            ], 403);
        }
        $multas->estado = 'cancelado';
        $multas->save();
        return response()->json([
            'message' => 'La multa ha sido cancelada. ',
            'multa' => new MultaResource($multas)
        ], 200);
    }

}