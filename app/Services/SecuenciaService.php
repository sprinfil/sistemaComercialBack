<?php
namespace App\Services;

class SecuenciaService{
    
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
    public function NuevaSecuencia($secuencia){

    }
    public function UpdateSecuencia($secuencia){

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