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
            $table->unsignedBigInteger('id_empleado_encargado')->nullable();
            $table->unsignedBigInteger('id_orden_trabajo_catalogo');
            $table->enum('estado',['No asignada','Concluida','En proceso','Cancelada']);
            $table->date('fecha_finalizada')->nullable();
            $table->date('fecha_vigencia')->nullable();
            $table->string('obervaciones')->nullable();
            $table->string('evidencia')->nullable();
            $table->string('material_utilizado')->nullable();
            $table->point('posicion_OT')->nullable();
            $table->softDeletes();
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
