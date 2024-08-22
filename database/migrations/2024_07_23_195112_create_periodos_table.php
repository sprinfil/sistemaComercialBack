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
        Schema::create('periodos', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('id_ruta');
            $table->unsignedInteger('id_tarifa');
            $table->date('facturacion_fecha_inicio');
            $table->date('facturacion_fecha_final');
            $table->date('lectura_inicio');
            $table->date('lectura_final');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('periodos');
    }
};
