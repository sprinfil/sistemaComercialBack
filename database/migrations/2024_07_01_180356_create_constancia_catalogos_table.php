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
        Schema::create('constancia_catalogos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_concepto_catalogo");
            $table->string("nombre");
            $table->string("descripcion");
            $table->enum('estado', ['activo', 'inactivo'])->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('constancia_catalogos');
    }
};
