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
        Schema::create('orden_trabajo_accions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_orden_trabajo_catalogo');
            $table->enum('accion',['registrar','modificar','quitar']);
            $table->string('modelo');
            $table->boolean('opcional')->nullable();
            $table->unsignedBigInteger('id_orden_trabajo_conf_encadena')->nullable();
            $table->unsignedBigInteger('id_orden_trabajo_conf_alterna')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_trabajo_accions');
    }
};