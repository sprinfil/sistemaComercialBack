<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use PhpParser\Node\Stmt\Return_;

class Factibilidad extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'factibilidad';

    protected $fillable = 
    [
        'id_contrato',
        'id_solicitante',
        'id_revisor',
        'estado',
        'agua_estado_factible',
        'alc_estado_factible',
        'san_estado_factible',
        'derechos_conexion',
        'documento'
    ];

    public function contrato () : ?BelongsTo
    {
        try{
            return $this->belongsTo(Contrato::class, 'id_contrato');
        }catch(Exception $ex){
            return null;
        }
        
    }

    public function solicitante(): HasOne
    {
        return $this->hasOne(Operador::class, 'id', 'id_solicitante');
    }

    public function revisor(): HasOne
    {
        return $this->hasOne(Operador::class, 'id', 'id_revisor');
    }
}