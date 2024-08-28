<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pago extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "pagos";

    protected $fillable = [
        "folio",
        "id_caja",
        "id_dueno",
        "modelo_dueno",
        "id_corte_caja",
        "total_pagado",
        "forma_pago",
        "fecha_pago",
        "estado",
        "timbrado",
    ];
    //Pagos con caja
    public function caja() : BelongsTo {
        return $this->belongsTo(Caja::class , 'id_caja'); 
    }

    //pagos con corte de caja
    public function corteCaja () : HasMany
    {
        return $this->hasMany(CorteCaja::class, 'id_pago'); 
    }

    public function dueno(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_dueno', 'id_dueno');
    }

    public function abonos(): MorphMany
    {
        return $this->morphMany(Abono::class, 'origen', 'modelo_origen', 'id_origen');
    }

    public function cargos()
    {
        return $this->abonos()->with('cargo')->get();
    }

    public function pendiente()
    {
        $abonos = $this->abonos;
        $total_aplicado = 0;
        foreach($abonos as $abono){
            $total_aplicado += $abono->total_abonado;
        }
        return $this->total_pagado - $total_aplicado;
    }
}
