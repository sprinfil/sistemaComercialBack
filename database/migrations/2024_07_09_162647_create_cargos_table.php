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
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_concepto');
            $table->string('nombre');
            $table->unsignedBigInteger('id_origen');
            $table->string('modelo_origen');
            $table->unsignedBigInteger('id_dueno');
            $table->string('modelo_dueno');
            $table->decimal('monto', 8, 2);
            $table->decimal('iva', 8, 2);
            $table->enum('estado', ['pendiente', 'pagado', 'conveniado', 'cancelado']);
            $table->unsignedBigInteger('id_convenio')->nullable();
            $table->date('fecha_cargo');
            $table->date('fecha_liquidacion')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index('id_origen');
            $table->index('modelo_origen');
            $table->index('estado');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cargos');
    }
};
