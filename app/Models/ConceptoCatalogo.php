<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ConceptoCatalogo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "nombre",
        "descripcion",
        "estado",
        "prioridad_abono",
        "prioridad_por_antiguedad",
        "genera_iva",
        "abonable",
        "categoria",
        "tipo_tarifa",
        "tarifa_fija",
        "cargo_directo",
        "genera_orden",
        "genera_recargo",
        "concepto_rezago",
        "pide_monto",
        "bonificable",
        "recargo"
    ];

    // Medidor asociado a la toma
    public function tarifas() : HasMany
    {
        return $this->hasMany(TarifaConceptoDetalle::class, 'id_concepto');
    }
    public function ordenAsignada() : HasOne
    {
        return $this->hasOne(OrdenTrabajoCatalogo::class, 'id', 'genera_orden')
                    ->select(['id', 'nombre']);
    }


    public function conceptoResago() : HasOne
    {
        return $this->hasOne(ConceptoCatalogo::class, 'id', 'concepto_rezago')
                    ->select(['id', 'nombre']);
    }

    public function ordenTrabajoCargos():HasMany
    {
        return $this->hasMany(OrdenesTrabajoCargo::class,'id_concepto_catalogo','id');
    }
    public function CotizacionDetalle():HasMany{
        return $this->hasMany(CotizacionDetalle::class,'id_concepto');
    }

    // Busqueda por nombre
    public static function buscarPorNombre(string $nombre){
        $data=ConceptoCatalogo::where('nombre',$nombre)->get()->first();
        return $data;
    }

    public function constancia():HasOne
    {
        return $this->hasOne(ConstanciaCatalogo::class,'id_concepto_catalogo','id');
    }
    public static function getRecargos(){
       return ConceptoCatalogo::find(10);
       //$id=10;
       //return 10;
    }
   
}
