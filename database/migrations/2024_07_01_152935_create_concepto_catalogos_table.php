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
        Schema::create('concepto_catalogos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->text("descripcion")->nullable();
            $table->enum('estado', ['activo', 'inactivo'])->nullable();
            $table->integer("prioridad_abono");
            $table->boolean("genera_iva")->nullable();
            $table->boolean("abonable")->nullable();
            $table->boolean("tarifa_fija")->nullable();
            $table->boolean("cargo_directo")->nullable();
            $table->unsignedBigInteger("genera_orden")->nullable();
            $table->boolean("genera_recargo")->nullable();
            $table->unsignedBigInteger("concepto_rezago")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepto_catalogos');
    }
};
