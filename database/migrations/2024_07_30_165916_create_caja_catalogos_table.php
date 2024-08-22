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
        Schema::create('caja_catalogos', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_caja', [['Zona Urbana'] , ['Centenario'], ['Todos Santos'] ,['El Pescadero'] , ['Los Barriles'] ,['El Sargento'] , ['Agua Amarga'] ,['Meliton Albañez'] , ['San Pedro'] , ['Reforma Agraria'], ['El Triunfo'] ,['Albaro Obregon'] , ['Elias Calles'],['Los Planes'] , ['San Bartolo'],['Juan Dominguez Cota'] , ['San Antonio'],['Conquista Agraria'] , ['El Carrizal'],['El Cardonal'], ['Las Pocitas'],['La trinidad'] , ['La Ventana']]);
            $table->time('hora_apertura');
            $table->time('hora_cierre');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('caja_catalogos');
    }
};
