<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orden_trabajos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_toma');
            $table->unsignedBigInteger('id_empleado_asigno');
            $table->unsignedBigInteger('id_empleado_encargado');
            $table->unsignedBigInteger('id_orden_trabajo_catalogo');
            $table->enum('estado',['No asignada','Concluida','En proceso']);
            $table->string('fecha_finalizada');
            $table->string('obervaciones');
            $table->string('evidencia');
            $table->string('material_utilizado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_trabajos');
    }
};
