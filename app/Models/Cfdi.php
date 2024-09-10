<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cfdi extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "folio",
        "id_timbro",
        "metodo",
        "estado",
        "id_datos_fiscales",
        "documento",
    ];

    public function pagos(): HasMany
    {
        return $this->hasMany(Pago::class, 'folio', 'folio');
    }    

    public function timbro() : BelongsTo
    {
        return $this->belongsTo(Operador::class, 'id_timbro', 'id');
    }

    public function datoFiscal() : HasOne
    {
        return $this->hasOne(DatoFiscal::class, 'id', 'id_datos_fiscales');
    }
}