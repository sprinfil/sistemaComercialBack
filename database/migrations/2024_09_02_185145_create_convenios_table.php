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
        Schema::create('convenios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_convenio_catalogo');
            $table->unsignedBigInteger('id_modelo');
            $table->enum('modelo_origen', ['toma', 'usuario']);
            $table->decimal('monto_conveniado')->nullable();
            $table->decimal('monto_total')->nullable();
            $table->enum('periodicidad', ['mensual', 'quincenal']);
            $table->integer('cantidad_letras');
            $table->enum('estado', ['activo', 'cancelado', 'incumplido']);
            $table->string('comentario')->nullable();
            $table->string('motivo_cancelacion')->nullable();
            $table->decimal('pago_inicial')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convenios');
    }
};
