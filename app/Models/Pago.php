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
        // ticket
        "total_abonado",
        "saldo_anterior",
        "saldo_pendiente",
        "saldo_a_favor",
        "recibido",
        "cambio",
        // estados
        "forma_pago",
        "fecha_pago",
        "estado",
        "referencia"
    ];

    // Pagos con caja
    public function caja(): BelongsTo {
        return $this->belongsTo(Caja::class , 'id_caja'); 
    }

    public function cfdi(): HasOne {
        return $this->hasOne(Cfdi::class, 'folio', 'folio');
    }    

    // Pagos con corte de caja
    public function corteCaja(): HasMany
    {
        return $this->hasMany(CorteCaja::class, 'id_pago'); 
    }

    public function dueno(): MorphTo
    {
        return $this->morphTo(__FUNCTION__, 'modelo_dueno', 'id_dueno');
    }

    public function duenoUsuario(): MorphTo
    {
        $dueno = $this->morphTo(__FUNCTION__, 'modelo_dueno', 'id_dueno');
        if ($dueno instanceof Toma) {
            // Si el modelo dueño es una toma, devuelve el usuario relacionado a esa toma
            return $dueno->usuario;
        }
        return $dueno;
    }

    public function abonos(): MorphMany
    {
        return $this->morphMany(Abono::class, 'origen', 'modelo_origen', 'id_origen');
    }

    public function cargos()
    {
        return $this->abonos()->with('cargo')->get();
    }

    public function abonosConCargos(): MorphMany
    {
        return $this->abonos()->with('cargo');
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

    public function total_abonado()
    {
        $abonos = $this->abonos;
        $total_aplicado = 0;
        foreach($abonos as $abono){
            $total_aplicado += $abono->total_abonado;
        }
        return $total_aplicado;
    }

    public function formatDueno()
    {
        if ($this->modelo_dueno === 'toma') {
            return $this->dueno;
        } elseif ($this->modelo_dueno === 'usuario') {
            return $this->dueno;
        }
        return null;
    }

    public function formatUsuario()
    {
        if ($this->modelo_dueno === 'toma') {
            return $this->dueno->usuario;
        } elseif ($this->modelo_dueno === 'usuario') {
            return $this->dueno;
        }
        return null;
    }
}