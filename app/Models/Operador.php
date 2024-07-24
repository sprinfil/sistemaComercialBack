<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Operador extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "operadores";
    protected $fillable = [
        "codigo_empleado",
        "nombre",
        "apellido_paterno",
        "apellido_materno",
        "CURP",
        "fecha_nacimiento",
    ];


    public function getNombreCompletoAttribute()
    {
        return $this->nombre . ' ' . $this->apellido_paterno . ' ' . $this->apellido_materno;
    }

    public function user()
    {
        return $this->belongsTo(User::class, "id_user", "id");
    }
    public function ordenTrabajoAsignar()
    {
        return $this->hasMany(ordenTrabajo::class, "id_empleado_asigno", "id");
    }
    public function ordenTrabajoEncargado()
    {
        return $this->hasMany(ordenTrabajo::class, "id_empleado_encargado", "id");
    }
    public function getRolesAttribute()
    {
        $user = $this->user;
        return $user->getRoleNames();
    }
    public function operadorEncargado() : HasMany {
        return $this->hasMany(CargaTrabajo::class, "id_operador_encargado");
    }
    public function operadorAsignado() : HasMany {
        return $this->hasMany(CargaTrabajo::class, "id_operador_asigno");
    }
}
