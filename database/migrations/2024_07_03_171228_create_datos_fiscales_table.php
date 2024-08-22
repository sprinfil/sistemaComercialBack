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
        Schema::create('datos_fiscales', function (Blueprint $table) {
            
            $table->id();
            $table->unsignedBigInteger('id_modelo')->default('1');
            $table->string('modelo');
            $table->string('regimen_fiscal');
            $table->string('nombre');
            $table->string('correo');
            $table->string('razon_social');
            $table->string('telefono',10);
            $table->string('pais');
            $table->string('estado');
            $table->string('municipio');
            $table->string('localidad');
            $table->string('colonia');
            $table->string('calle');
            $table->string('referencia');
            $table->string('numero_exterior')->nullable();
            $table->string('codigo_postal');
            $table->bigInteger('contacto')->unsigned()->default('1'); //llave foranea que apunta a la tabla de contactos de facturas CFDI, esta pendiente
            $table->softDeletes();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datos_fiscales');
    }
};
