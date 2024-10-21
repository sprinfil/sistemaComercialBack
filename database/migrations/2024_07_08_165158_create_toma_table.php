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
            $table->unsignedBigInteger('id_usuario')->nullable();
            $table->unsignedBigInteger('id_giro_comercial')->nullable();
            $table->unsignedBigInteger('id_libro')->nullable();
            $table->string('codigo_toma')->nullable();
            $table->unsignedBigInteger('id_tipo_toma');
            $table->string('clave_catastral')->nullable();
            $table->enum('estatus', ['pendiente confirmación inspección', 'pendiente de inspeccion', 'pendiente de instalacion', 'activa', 'baja definitiva', 'baja temporal', 'en proceso','limitado']);
            $table->unsignedBigInteger('calle');
            $table->unsignedBigInteger('entre_calle_1')->nullable();
            $table->unsignedBigInteger('entre_calle_2')->nullable();
            $table->unsignedBigInteger('colonia');
            $table->string('codigo_postal');
            $table->string('numero_casa')->nullable();
            $table->enum('localidad', ['La Paz','Todos santos','Chametla','El Centenario','El Pescadero','Colonia Calafia','El Sargento','El Carrizal','San Pedro','Agua Amarga','Los Barriles','Buena Vista','San Bartolo','San Juan de los Planes','La Matanza','Puerto Chale']);
            $table->enum('diametro_toma',[' 1/2 pulgada','1/4 pulgada','1 pulgada','2 pulgadas','1/8 pulgada'])->nullable();
            $table->string('direccion_notificacion');
            $table->enum('tipo_servicio',['lectura','promedio']);
            $table->unsignedBigInteger('c_agua')->nullable();
            $table->unsignedBigInteger('c_alc')->nullable();
            $table->unsignedBigInteger('c_san')->nullable();
            $table->enum('tipo_contratacion', ['normal', 'condicionado', 'pre-contrato']);
            $table->date('fecha_instalacion')->nullable();
            $table->point('posicion')->nullable(); 
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
