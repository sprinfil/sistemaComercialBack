<?php
namespace App\Services;

use App\Models\Secuencia;
use App\Models\Secuencia_orden;
use App\Models\Toma;
use ErrorException;
use Exception;
use Illuminate\Contracts\Database\Query\Builder;

class SecuenciaService{
    /*
    public function SecuenciaStore($secuencia){
        //Guardar y actualizar secuencias
        //no de secuencias padres??


        ///Recibe id secuencia, si es nulo es un create doble, si no es nulo es un createordUpdate de orden_secuencia
        //Si el tipo es una secuencia tipo personalizada, recibe id_operador, esto lo valido en el request.

        $id_secuencia=$secuencia['id_secuencia'] ?? null;
        if  ($id_secuencia){
            //Sucuencia nueva, usa Create y Create
        }
        else{
            //Secuencia ya existente, usa Update y createOrUpdate.
            //Las tomas que no tengan ordenes secuencia seran eliminadas.

            //Las tomas que no esten en la secuencia padre, se buscan y se borran de la secuencia personalizada.
        }

        //Devuelvo la secuencia padre y las secuencias ordenes hijas

    }
        */
    public function Store($secuencia){
        $id_secuencia=$secuencia['id'] ?? null;
        $tipo=$secuencia['tipo_secuencia'];

        
        
        if ($tipo=="personalizada"){
            ///Existe personalizada igual
            $Existe=Secuencia::where('id_libro',$secuencia['id_libro'])->where('id_empleado',$secuencia['id_empleado'] ?? null)->where('id',$id_secuencia)->first();
            if($Existe && !$id_secuencia){
                return "Personalizada";
            }
            else if($Existe && $Existe['tipo_secuencia']!=$secuencia['tipo_secuencia']){
                return "No perso";
            }
            $NuevaSecuencia=Secuencia::UpdateOrCreate(['id'=>$id_secuencia],$secuencia); //Secuencia personalizada
        }
        else{
            //Existe secuencia padre igual
            $Existe=Secuencia::where('id_libro',$secuencia['id_libro'])->where('tipo_secuencia',"padre")->first();
            if($Existe && !$id_secuencia){
                return "Padre";
            }
            if ($id_secuencia){
                ///Si la secuencia padre ya existe, no se le mueve nada, no deberia ser fácil mover una secuencia padre.
                $id_operador=$secuencia['id_empleado'] ?? null;
                if ($id_operador){
                    return "Operador";
                }
                $NuevaSecuencia=Secuencia::find($id_secuencia);
                if ($NuevaSecuencia['id_libro']!=$secuencia['id_libro'] || $NuevaSecuencia['tipo_secuencia']!=$secuencia['tipo_secuencia'] ){
                    return "Invalido";
                }
            }
            else{
                $NuevaSecuencia=Secuencia::create($secuencia);
            }
           
        }
        
        return $NuevaSecuencia;
    }
    public function SecuenciaOrdenStore($secuencia_padre,$ordenes){
        $id_secuencia=$secuencia_padre['id'];
        $secuenciaOrden=[];
        $ids = [];
        $tomaLibro=Toma::whereIn('id',array_column($ordenes,'id_toma'))->whereNot('id_libro',$secuencia_padre['id_libro'])->get();
        //$secOrd=Secuencia_orden::where('id_secuencia',$secuencia_padre['id'])->get();

        if (count($tomaLibro)!=0){
            $errores=null;
            foreach ($tomaLibro as $error){
                $errores=$errores.$error['codigo_toma'].",";
            }
            throw new Exception("La(s) toma(s) con código: ".$errores .". No pertenece al libro de la secuencia o su numero de secuencia se repite.",400);
            
        }
        $counts = array_column($ordenes,'numero_secuencia');
        $unicos=array_unique($counts);
        $filtered = array_filter($unicos, function($value) {
            return $value !== 0;
        });
  
       if (count($counts)!=count($filtered)){
        throw new ErrorException("No se pueden subir tomas que tengan el mismo numero de orden de secuencia, asigne ordenes differentes para cada toma en la secuencia",400);
       }

        foreach ($ordenes as $orden){
            $id=$orden['id'] ?? null;
            $secuenciaOrden[]=[
                "id"=>$id,
                "id_secuencia"=>$id_secuencia,
                "id_toma"=>$orden['id_toma'],
                "numero_secuencia"=>$orden['numero_secuencia'],
            ];
            if ($id) {
                $ids[] = $id;
            }
        }
        //Secuencia_orden::where('id_secuencia',$id_secuencia)->whereNotIn('id',$ids)->delete();
        $Ordenado=Secuencia_orden::upsert($secuenciaOrden, uniqueBy: ['id']);
        $updatedRecords = Secuencia_orden::where('id_secuencia',$id_secuencia)->orWhereIn('id', $ids)->get();
        return $updatedRecords;
    }
    public function DeleteSecuencia(){
        //Borra una secuencia padre/personalizada y sus secuencias ordenes
        //Si borra una secuencia padre, se borran todas las personalizadas asociadas.
    }
    public function Filtros($parametros){
        //libro
        //ruta
        //codigo_empleado
        //tipo de secuencia


    }
}