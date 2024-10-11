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
        Schema::create('multas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_multado');
            $table->unsignedBigInteger('id_catalogo_multa');
            $table->unsignedBigInteger('id_operador')->nullable();
            $table->unsignedBigInteger('id_revisor')->nullable();
            $table->enum('modelo_multado', ['toma' , 'usuario'])->nullable();
            $table->string('motivo');
            $table->date('fecha_solicitud')->nullable();
            $table->date('fecha_revision')->nullable();
            $table->integer('monto')->nullable();
            $table->enum('estado', ['activo' , 'rechazado' , 'pendiente' , 'cancelado'])->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('multas');
    }
};
