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
        Schema::create('constancias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_catalogo_constancia');
            $table->enum('estado', ['pendiente', 'pagado','cancelado']);
            $table->unsignedBigInteger('id_operador');
            $table->unsignedBigInteger('id_dueno');
            $table->enum('modelo_dueno', ['toma', 'usuario']);
            $table->dateTime('vigencia')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constancias');
    }
};
