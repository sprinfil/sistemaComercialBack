<?php
namespace App\Services;

use App\Models\Secuencia;
use App\Models\Secuencia_orden;
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

        
        $Existe=Secuencia::where('id_libro',$secuencia['id_libro'])->first();
        if($Existe && !$id_secuencia){
            return null;
        }
        if ($tipo=="personalizada"){
            $NuevaSecuencia=Secuencia::UpdateOrCreate(['id'=>$id_secuencia],$secuencia); //Secuencia personalizada
        }
        else{
            if ($id_secuencia){
                ///Si la secuencia padre ya existe, no se le mueve nada, no deberia ser fácil mover una secuencia padre.
                $NuevaSecuencia=$Existe;
                if ($NuevaSecuencia!=$secuencia){
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
        $Ordenado=Secuencia_orden::upsert($secuenciaOrden, uniqueBy: ['id']);
        $updatedRecords = Secuencia_orden::where('id_secuencia',$id_secuencia)->orWhereIn('id', $ids)->get();
        return $updatedRecords;
    }
    /*
    public function UpdateSecuencia($secuencia){

    }
    */
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