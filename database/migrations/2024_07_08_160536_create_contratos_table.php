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
            $table->unsignedBigInteger('id_usuario');
            $table->string('folio_solicitud')->unique();
            $table->enum('estatus', [
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
            $table->string('tipo_toma');
            $table->string('colonia');
            $table->string('calle');
            $table->string('entre_calle1')->nullable();
            $table->string('entre_calle2')->nullable();
            $table->string('domicilio');
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
