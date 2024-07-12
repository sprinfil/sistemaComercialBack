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
        Schema::create('toma', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_giro_comercial');
            $table->unsignedBigInteger('id_libro');
            $table->unsignedBigInteger('id_codigo_toma');

            $table->string('clave_catastral');
            $table->string('estatus');
            $table->string('calle');
            $table->string('entre_calle_1')->nullable();
            $table->string('entre_calle_2')->nullable();
            $table->string('colonia');
            $table->string('codigo_postal');
            $table->string('localidad');
            $table->string('diametro_toma');
            $table->string('calle_notificaciones');
            $table->string('entre_calle_notificaciones_1')->nullable();
            $table->string('entre_calle_notificaciones_2')->nullable();
            $table->string('tipo_servicio');
            $table->enum('tipo_toma', ['domestica', 'comercial', 'industrial']);
            $table->unsignedBigInteger('c_agua')->nullable();
            $table->unsignedBigInteger('c_alc_san')->nullable();
            $table->string('tipo_contratacion');
            $table->softDeletes();
            $table->timestamps();
        });
       
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('toma');
    }
};
