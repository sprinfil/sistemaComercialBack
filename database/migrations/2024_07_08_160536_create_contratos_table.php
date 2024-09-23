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
            $table->unsignedBigInteger('colonia');
            $table->enum('municipio',['La Paz', 'Los cabos','Comondu']);
            $table->enum('localidad', ['La Paz','Todos santos','Chametla','El Centenario','El Pescadero','Colonia Calafia','El Sargento','El Carrizal','Agua Amarga','Los Barriles','Buena Vista','San Bartolo','San Pedro','San Juan de los Planes','La Matanza','Puerto Chale']);
            $table->unsignedBigInteger('calle');
            $table->unsignedBigInteger('entre_calle1')->nullable();
            $table->unsignedBigInteger('entre_calle2')->nullable();
            $table->string('num_casa')->nullable();
            $table->enum('diametro_toma',[' 1/2 pulgada','1/4 pulgada','1 pulgada','2 pulgadas','1/8 pulgada'])->nullable();
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
