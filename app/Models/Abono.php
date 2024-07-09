<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Abono extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "id_pago",
        "id_cargo",
        "id_origen",
        "modelo_origen",
        "total_abonado",
    ];
}