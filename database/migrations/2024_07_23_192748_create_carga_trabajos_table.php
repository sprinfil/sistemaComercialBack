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
        Schema::create('carga_trabajos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_libro');
            $table->unsignedInteger('id_operador_encargado')->nullable();
            $table->unsignedInteger('id_periodo'); ///tiene las lecturas
            $table->unsignedInteger('id_operador_asigno')->nullable();
            $table->enum('estado', ['no asignada', 'en proceso', 'concluida', 'cancelada']);
            $table->date('fecha_concluida')->nullable();
            $table->date('fecha_asignacion')->nullable();
            $table->enum('tipo_carga' , ['lectura' , 'facturacion' , 'facturacion en sitio'])->nullable();///duda
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carga_trabajos');
    }
};
