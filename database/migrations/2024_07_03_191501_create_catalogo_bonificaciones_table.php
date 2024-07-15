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
        Schema::create('catalogo_bonificaciones', function (Blueprint $table) {
            $table->id();
            $table->string("nombre")->nullable();
            $table->string("descripcion")->nullable();
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
        Schema::dropIfExists('catalogo_bonificaciones');
    }
};
