<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrdenTrabajoConfiguracion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        "id_orden_trabajo_catalogo",
        "id_concepto_catalogo",
        "accion",
        "momento",
       
    ];
    public function orden_trabajo_catalogo(): BelongsTo{
        return $this->BelongsTo(OrdenTrabajoCatalogo::class,'id_orden_trabajo_catalogo', 'id');
    }
    public function id_concepto_catalogo(): BelongsTo{
        return $this->BelongsTo(ConceptoCatalogo::class,'id_concepto_catalogo', 'id');
    }
}
