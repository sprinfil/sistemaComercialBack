<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


class OrdenTrabajoAccion extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable=[
        "id_orden_trabajo_catalogo",
        "accion",
        "modelo",
        "opcional",
        "id_orden_trabajo_acc_encadena",
        "id_orden_trabajo_acc_alterna",
       
    ];
    public function orden_trabajo_catalogo(): BelongsTo{
        return $this->BelongsTo(OrdenTrabajoCatalogo::class,'id_orden_trabajo_catalogo', 'id');
    }
}
