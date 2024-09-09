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
        Schema::create('factibilidad', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_contrato');
            $table->unsignedBigInteger('id_solicitante');
            $table->unsignedBigInteger('id_revisor')->nullable();
            $table->enum('estado', ['pendiente', 'rechazada', 'pendiente de pago', 'pagada'])->default('pendiente');
            $table->enum('agua_estado_factible', ['pendiente','no_factible', 'factible'])->default('pendiente');
            $table->enum('alc_estado_factible', ['pendiente','no_factible', 'factible'])->default('pendiente');
            $table->enum('san_estado_factible', ['pendiente','no_factible', 'factible'])->default('pendiente');
            $table->decimal('derechos_conexion' , total:8 , places:2)->nullable(); //Cambio a decimal.
            $table->string('documento')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factibilidad');
    }
};
