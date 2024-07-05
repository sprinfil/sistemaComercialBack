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
        Schema::create('dato_fiscales', function (Blueprint $table) {
            
            $table->id();
            $table->string('regimen_fiscal');
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
            $table->string('tipo_modelo');
            $table->softDeletes();
            $table->timestamps();
 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dato_fiscales');
    }
};
