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
        Schema::create('ajustes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_ajuste_catalogo');
            $table->unsignedBigInteger('id_modelo_dueno');
            $table->enum('modelo_dueno', ['toma', 'usuario']);
            $table->unsignedBigInteger('id_operador');
            $table->decimal('monto_ajustado')->nullable();
            $table->decimal('monto_total')->nullable();
            $table->enum('estado', ['activo', 'cancelado', 'incumplido']);
            $table->string('comentario')->nullable();
            $table->string('motivo_cancelacion')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ajustes');
    }
};
