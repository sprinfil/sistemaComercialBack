<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class cargoDirecto extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "cargo_directo";
       
    protected $fillable = [
        "id_cargo", 
        /*
        "id_origen",
        "modelo_origen",
        "id_dueño",
        "modelo_dueño",
        "monto",
        "estado",
        "fecha_cargo",
        "fecha_liquidacion", 
        */
    ];

    public function cargos () 
    {
        return $this->belongsTo(Cargo::class);
    }
    
}
