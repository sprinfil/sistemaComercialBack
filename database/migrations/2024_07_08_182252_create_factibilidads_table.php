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
            $table->unsignedBigInteger('id_toma');
            $table->unsignedBigInteger('id_solicitante');
            $table->unsignedBigInteger('id_revisor')->nullable();
            $table->enum('estado', ['sin revisar', 'rechazada', 'pendiente de pago', 'pagada'])->default('sin revisar');
            $table->enum('servicio', ['agua', 'alcantarillado y saneamiento']);
            $table->enum('estado_servicio', ['pendiente', 'no factible', 'factible'])->default('pendiente');
            //$table->enum('alc_estado_factible', ['pendiente', 'no factible', 'factible'])->default('pendiente');
            //$table->enum('san_estado_factible', ['pendiente', 'no factible', 'factible'])->default('pendiente');
            $table->decimal('derechos_conexion', total: 8, places: 2)->nullable(); //Cambio a decimal.
            //$table->string('documento')->nullable();
            $table->string('comentario')->nullable();
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
