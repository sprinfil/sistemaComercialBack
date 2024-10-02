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
        Schema::create('cargos_conveniados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_cargo');
            $table->unsignedBigInteger('id_convenio');
            $table->decimal('monto_original_pendiente');
            $table->decimal('monto_final_pendiente');
            $table->decimal('porcentaje_conveniado');
            $table->decimal('monto_conveniado');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos_conveniados');
    }
};
