<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class secuencia extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "secuencias";
    protected $fillable = [
        "id_empleado",
        "id_libro",
    ];
}
