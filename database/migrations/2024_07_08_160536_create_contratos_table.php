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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_toma');
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->string('folio_solicitud')->unique();
            $table->enum('estatus', [
                'pendiente de factibilidad',
                'pendiente de inspeccion', 
                'contrato no factible', 
                'inspeccionado', 
                'pendiente de pago', 
                'contratado',
                'terminado', 
                'cancelado'
            ]);
            $table->string('nombre_contrato');
            $table->string('clave_catastral')->nullable();
            $table->unsignedBigInteger('tipo_toma');
            $table->enum('servicio_contratado', ['agua', 'alcantarillado y saneamiento']);
            $table->string('colonia');
            $table->string('municipio');
            $table->string('localidad');
            $table->string('calle');
            $table->string('entre_calle1')->nullable();
            $table->string('entre_calle2')->nullable();
            $table->string('num_casa')->nullable();
            $table->string('diametro_de_la_toma');
            $table->string('codigo_postal');
            $table->string('coordenada')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
