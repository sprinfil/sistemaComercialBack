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
        Schema::create('correccion_informacion_solicitudes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_tipo");
            $table->unsignedBigInteger("id_empleado_solicita");
            $table->unsignedBigInteger("id_empleado_registra");

            $table->enum("tipo_correccion",['Toma','Medidor','Informacion personal']);
            $table->date("fecha_solicitud");
            $table->date("fecha_correccion")->nullable();
            $table->string("comentario")->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('correccion_informacion_solicitudes');
    }
};
